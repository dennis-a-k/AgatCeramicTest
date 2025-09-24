<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Color;
use App\Repositories\Contracts\FilterableRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductRepository implements FilterableRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->published()->get();
    }

    public function find($id): ?Product
    {
        return $this->model->with(['category', 'brand', 'color', 'attributeValues.attribute'])
            ->published()
            ->find($id);
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): bool
    {
        return $this->model->find($id)->update($data);
    }

    public function delete($id): bool
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Получение всех ID категории, включая дочерние
     *
     * @param Category $category
     * @return array
     */
    protected function getCategoryIds(Category $category): array
    {
        return $category->children->pluck('id')->push($category->id)->toArray();
    }
    // Добавляем методы для работы с фильтрами (начало нового)
    /**
     * Получение товаров по slug категории с фильтрами
     *
     * @param string $slug Slug категории
     * @param Request $request Параметры фильтрации
     * @return array
     */
    public function getByCategorySlug($slug, Request $request): array
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $categoryIds = $this->getCategoryIds($category);

        // Создаем клон запроса с добавленным category_id
        $newRequest = clone $request;
        $newRequest->merge(['category_ids' => $categoryIds]);

        $products = $this->applyFilters($newRequest);
        $filters = $this->getAvailableFilters($newRequest);

        // Добавляем subcategories
        $filters['subcategories'] = $category->children->map(function ($child) {
            return [
                'id' => $child->id,
                'name' => $child->name,
                'slug' => $child->slug,
            ];
        })->toArray();

        Log::info('Category filters for ' . $slug, $filters);

        return [
            'category' => $category,
            'products' => $products,
            'filters' => $filters
        ];
    }
    // конец нового

    // Метод для применения фильтров
    public function applyFilters(Request $request): LengthAwarePaginator
    {
        $query = $this->model->with(['category', 'brand', 'color', 'attributeValues.attribute'])
            ->published();

        // Поиск
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Фильтрация по категории
        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);
            if ($category) {
                $categoryIds = $this->getCategoryIds($category);
                $query->whereIn('category_id', $categoryIds);
            }
        } elseif ($request->has('category_ids')) {
            $query->whereIn('category_id', (array)$request->category_ids);
        }

        // Фильтрация по бренду
        if ($request->has('brand_id')) {
            $query->whereIn('brand_id', (array)$request->brand_id);
        }

        // Фильтрация по цвету
        if ($request->has('color_id')) {
            $query->whereIn('color_id', (array)$request->color_id);
        }

        // Фильтрация по цене
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Фильтрация по распродаже
        if ($request->has('is_sale')) {
            $query->onSale();
        }

        // Динамическая фильтрация по атрибутам
        if ($request->has('attributes')) {
            $attributes = $request->attributes;
            foreach ($attributes as $attributeId => $values) {
                if (!empty($values)) {
                    $query->whereHas('attributeValues', function ($q) use ($attributeId, $values) {
                        $attribute = Attribute::find($attributeId);
                        $q->where('attribute_id', $attributeId);

                        if ($attribute->type === 'number') {
                            $q->whereIn('number_value', (array)$values);
                        } else {
                            $q->whereIn('string_value', (array)$values);
                        }
                    });
                }
            }
        }

        // Сортировка
        $sortOption = $request->get('sort', 'default'); // Получаем опцию сортировки из запроса

        switch ($sortOption) {
            case 'alphabetical':
                $query->orderBy('name', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'default':
            case 'newest': // Добавляем обработку 'newest' для совместимости
                $query->orderBy('created_at', 'desc');
                break;
            default:
                // По умолчанию сортируем по 'created_at' desc, если опция неизвестна
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Пагинация
        $perPage = $request->get('per_page', 24);
        return $query->paginate($perPage);
    }

    public function getAvailableFilters(Request $request): array
    {
        $baseQuery = $this->model->published();

        // Применяем те же фильтры, что и в основном запросе
        $category = null;
        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);
            if ($category) {
                $categoryIds = $this->getCategoryIds($category);
                $baseQuery->whereIn('category_id', $categoryIds);
            }
        } elseif ($request->has('category_ids')) {
            $baseQuery->whereIn('category_id', (array)$request->category_ids);
            // Для получения атрибутов, берем первую категорию из массива
            $categoryId = is_array($request->category_ids) ? $request->category_ids[0] : $request->category_ids;
            $category = Category::find($categoryId);
        }

        if ($request->has('brand_id')) {
            $baseQuery->whereIn('brand_id', (array)$request->brand_id);
        }

        // Получаем доступные бренды
        $brands = Brand::whereHas('products', function ($q) use ($baseQuery) {
                $q->whereIn('products.id', $baseQuery->select('id'));
            })
            ->withCount(['products' => function ($q) use ($baseQuery) {
                $q->whereIn('products.id', $baseQuery->select('id'));
            }])
            ->get();

        // Получаем доступные цвета
        $colors = Color::whereHas('products', function ($q) use ($baseQuery) {
                $q->whereIn('products.id', $baseQuery->select('id'));
            })
            ->withCount(['products' => function ($q) use ($baseQuery) {
                $q->whereIn('products.id', $baseQuery->select('id'));
            }])
            ->get();

        // Получаем уникальные значения из полей products
        $patternsQuery = clone $baseQuery;
        $patterns = $patternsQuery->select('pattern as value', DB::raw('COUNT(*) as count'))
            ->whereNotNull('pattern')
            ->groupBy('pattern')
            ->get()
            ->map(fn($item) => ['id' => $item->value, 'name' => $item->value, 'count' => $item->count])
            ->toArray();

        $texturesQuery = clone $baseQuery;
        $textures = $texturesQuery->select('texture as value', DB::raw('COUNT(*) as count'))
            ->whereNotNull('texture')
            ->groupBy('texture')
            ->get()
            ->map(fn($item) => ['id' => $item->value, 'name' => $item->value, 'count' => $item->count])
            ->toArray();

        // Инициализируем массивы для атрибутов
        $weights = [];
        $glues = [];
        $mixture_types = [];
        $seams = [];
        $sizes = [];

        if ($category) {
            $category = Category::with('filterableAttributes')->find($category->id);
            foreach ($category->filterableAttributes as $attribute) {
                $valuesQuery = DB::table('product_attribute_values')
                    ->join('products', 'product_attribute_values.product_id', '=', 'products.id')
                    ->where('product_attribute_values.attribute_id', $attribute->id)
                    ->whereIn('products.id', $baseQuery->select('id'))
                    ->where('products.is_published', true);

                $values = [];
                if ($attribute->type === 'number') {
                    $values = $valuesQuery->select('number_value as value', DB::raw('COUNT(*) as count'))
                        ->whereNotNull('number_value')
                        ->groupBy('number_value')
                        ->get()
                        ->map(function ($item) {
                            return ['id' => $item->value, 'value' => $item->value, 'count' => $item->count];
                        })
                        ->toArray();
                } else {
                    $values = $valuesQuery->select('string_value as value', DB::raw('COUNT(*) as count'))
                        ->whereNotNull('string_value')
                        ->groupBy('string_value')
                        ->get()
                        ->map(function ($item) {
                            return ['id' => $item->value, 'name' => $item->value, 'count' => $item->count];
                        })
                        ->toArray();
                }

                // Распределяем по slug
                switch ($attribute->slug) {
                    case 'tolshhina':
                        $weights = $values; // толщина как вес
                        break;
                    case 'glue':
                        $glues = $values;
                        break;
                    case 'mixture_type':
                        $mixture_types = $values;
                        break;
                    case 'seam':
                        $seams = $values;
                        break;
                    case 'size':
                        $sizes = $values;
                        break;
                }
            }
        }

        // Ценовой диапазон
        $priceRange = [
            'min' => $baseQuery->min('price'),
            'max' => $baseQuery->max('price')
        ];

        $result = [
            'brands' => $brands,
            'colors' => $colors,
            'patterns' => $patterns,
            'weights' => $weights,
            'glues' => $glues,
            'mixture_types' => $mixture_types,
            'seams' => $seams,
            'textures' => $textures,
            'sizes' => $sizes,
            'price_range' => $priceRange,
            'has_sale' => $baseQuery->where('is_sale', true)->exists()
        ];

        Log::info('Available filters result', $result);

        return $result;
    }
}
