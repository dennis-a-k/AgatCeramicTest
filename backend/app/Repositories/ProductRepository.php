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
                $categoryIds = $category->children->pluck('id')->push($category->id);
                $query->whereIn('category_id', $categoryIds);
            }
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
                            if (isset($values['min'])) {
                                $q->where('number_value', '>=', $values['min']);
                            }
                            if (isset($values['max'])) {
                                $q->where('number_value', '<=', $values['max']);
                            }
                        } else {
                            $q->whereIn('string_value', (array)$values);
                        }
                    });
                }
            }
        }

        // Сортировка
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('order', 'desc');

        $allowedSortFields = ['price', 'name', 'created_at', 'article'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Пагинация
        $perPage = $request->get('per_page', 24);
        return $query->paginate($perPage);
    }

    public function getAvailableFilters(Request $request): array
    {
        $baseQuery = $this->model->published();

        // Применяем те же фильтры, что и в основном запросе
        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);
            if ($category) {
                $categoryIds = $category->children->pluck('id')->push($category->id);
                $baseQuery->whereIn('category_id', $categoryIds);
            }
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

        // Получаем доступные атрибуты
        $attributes = [];
        if ($request->has('category_id')) {
            $category = Category::with('filterableAttributes')->find($request->category_id);
            if ($category) {
                foreach ($category->filterableAttributes as $attribute) {
                    $valuesQuery = DB::table('product_attribute_values')
                        ->join('products', 'product_attribute_values.product_id', '=', 'products.id')
                        ->where('product_attribute_values.attribute_id', $attribute->id)
                        ->whereIn('products.id', $baseQuery->select('id'))
                        ->where('products.is_published', true);

                    if ($attribute->type === 'number') {
                        $min = $valuesQuery->min('number_value');
                        $max = $valuesQuery->max('number_value');
                        $attributes[$attribute->id] = [
                            'attribute' => $attribute,
                            'min' => $min,
                            'max' => $max,
                            'type' => 'range'
                        ];
                    } else {
                        $values = $valuesQuery->select('string_value', DB::raw('COUNT(*) as count'))
                            ->groupBy('string_value')
                            ->get();
                        $attributes[$attribute->id] = [
                            'attribute' => $attribute,
                            'values' => $values,
                            'type' => 'select'
                        ];
                    }
                }
            }
        }

        // Ценовой диапазон
        $priceRange = [
            'min' => $baseQuery->min('price'),
            'max' => $baseQuery->max('price')
        ];

        return [
            'brands' => $brands,
            'colors' => $colors,
            'attributes' => $attributes,
            'price_range' => $priceRange,
            'has_sale' => $baseQuery->where('is_sale', true)->exists()
        ];
    }
}
