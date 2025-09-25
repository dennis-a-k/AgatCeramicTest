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

        // Строим baseQuery для subcategories
        $baseQuery = $this->model->published();
        if ($request->has('category_id')) {
            $cat = Category::find($request->category_id);
            if ($cat) {
                $categoryIds = $this->getCategoryIds($cat);
                $baseQuery->whereIn('category_id', $categoryIds);
            }
        } elseif ($request->has('category_ids')) {
            $baseQuery->whereIn('category_id', (array)$request->category_ids);
        }
        // Добавляем остальные фильтры аналогично getAvailableFilters
        if ($request->has('brands') && !empty($request->brands)) {
            $baseQuery->whereIn('brand_id', array_map('intval', (array)$request->brands));
        }
        if ($request->has('colors') && !empty($request->colors)) {
            $baseQuery->whereIn('color_id', array_map('intval', (array)$request->colors));
        }
        if ($request->has('patterns') && !empty($request->patterns)) {
            $baseQuery->whereIn('pattern', (array)$request->patterns);
        }
        if ($request->has('textures') && !empty($request->textures)) {
            $baseQuery->whereIn('texture', (array)$request->textures);
        }
        if ($request->has('subcategories') && !empty($request->subcategories)) {
            $baseQuery->whereIn('category_id', array_map('intval', (array)$request->subcategories));
        }
        // Фильтрация по коллекции
        if ($request->has('collections') && !empty($request->collections)) {
            $baseQuery->whereIn('collection', (array)$request->collections);
        }
        // Фильтрация по весу (толщина)
        if ($request->has('weights') && !empty($request->weights)) {
            $attribute = Attribute::where('slug', 'tolshhina')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->weights);
                });
            }
        }

        // Фильтрация по клею
        if ($request->has('glues') && !empty($request->glues)) {
            $attribute = Attribute::where('slug', 'ispolzuetsia-v-kacestve-kleia')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    // Convert "0"/"1" strings back to boolean values for filtering
                    $booleanValues = array_map(function($value) {
                        return $value === '1' || $value === 1 || $value === true || $value === 'true';
                    }, (array)$request->glues);
                    $q->where('attribute_id', $attribute->id)->whereIn('boolean_value', $booleanValues);
                });
            }
        }

        // Фильтрация по типу смеси
        if ($request->has('mixture_types') && !empty($request->mixture_types)) {
            $attribute = Attribute::where('slug', 'tip')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->mixture_types);
                });
            }
        }

        // Фильтрация по шву
        if ($request->has('seams') && !empty($request->seams)) {
            $attribute = Attribute::where('slug', 'sirina-sva')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->seams);
                });
            }
        }

        // Фильтрация по размеру
        if ($request->has('sizes') && !empty($request->sizes)) {
            $attribute = Attribute::where('slug', 'razmery')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->sizes);
                });
            }
        }

        // Фильтрация по материалу
        if ($request->has('materials') && !empty($request->materials)) {
            $attribute = Attribute::where('slug', 'material')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->materials);
                });
            }
        }

        // Фильтрация по влагостойкости
        if ($request->has('waterproofs') && !empty($request->waterproofs)) {
            $attribute = Attribute::where('slug', 'vlagostoikost')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    // Convert "0"/"1" strings back to boolean values for filtering
                    $booleanValues = array_map(function($value) {
                        return $value === '1' || $value === 1 || $value === true || $value === 'true';
                    }, (array)$request->waterproofs);
                    $q->where('attribute_id', $attribute->id)->whereIn('boolean_value', $booleanValues);
                });
            }
        }

        // Фильтрация по объему
        if ($request->has('volumes') && !empty($request->volumes)) {
            $attribute = Attribute::where('slug', 'obem')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->volumes);
                });
            }
        }

        // Фильтрация по весу
        if ($request->has('product_weights') && !empty($request->product_weights)) {
            $attribute = Attribute::where('slug', 'ves')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->product_weights);
                });
            }
        }

        // Фильтрация по типу установки
        if ($request->has('installation_types') && !empty($request->installation_types)) {
            $attribute = Attribute::where('slug', 'tip-ustanovki')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->installation_types);
                });
            }
        }

        // Фильтрация по форме
        if ($request->has('shapes') && !empty($request->shapes)) {
            $attribute = Attribute::where('slug', 'forma')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->shapes);
                });
            }
        }

        // Фильтрация по применению
        if ($request->has('applications') && !empty($request->applications)) {
            $attribute = Attribute::where('slug', 'primenenie')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->applications);
                });
            }
        }

        // Фильтрация по времени высыхания
        if ($request->has('drying_times') && !empty($request->drying_times)) {
            $attribute = Attribute::where('slug', 'vremya-vysykhaniya')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->drying_times);
                });
            }
        }

        // Фильтрация по весу упаковки
        if ($request->has('package_weights') && !empty($request->package_weights)) {
            $attribute = Attribute::where('slug', 'ves-upakovki')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->package_weights);
                });
            }
        }

        // Фильтрация по мин. температуре
        if ($request->has('min_temps') && !empty($request->min_temps)) {
            $attribute = Attribute::where('slug', 'min-temperatura-ekspluatatsii')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->min_temps);
                });
            }
        }

        // Фильтрация по макс. температуре
        if ($request->has('max_temps') && !empty($request->max_temps)) {
            $attribute = Attribute::where('slug', 'maks-temperatura-ekspluatatsii')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->max_temps);
                });
            }
        }

        // Фильтрация по расходу
        if ($request->has('consumptions') && !empty($request->consumptions)) {
            $attribute = Attribute::where('slug', 'rashod')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->consumptions);
                });
            }
        }

        // Добавляем subcategories
        $filters['subcategories'] = $category->children()->whereHas('products', function ($q) use ($baseQuery) {
            $q->whereIn('products.id', $baseQuery->select('id'));
        })->withCount(['products' => function ($q) use ($baseQuery) {
            $q->whereIn('products.id', $baseQuery->select('id'));
        }])->get()->map(function ($child) {
            return [
                'id' => $child->id,
                'name' => $child->name,
                'slug' => $child->slug,
                'count' => $child->products_count,
            ];
        })->toArray();

        return [
            'category' => $category,
            'products' => $products,
            'filters' => $filters
        ];
    }

    // Метод для применения фильтров
    public function applyFilters(Request $request): LengthAwarePaginator
    {
        $query = $this->model->with(['category', 'brand', 'color', 'attributeValues.attribute'])->published();

        // Поиск
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Фильтрация по категории
        $categoryIdsToUse = null;
        if ($request->has('subcategories') && !empty($request->subcategories)) {
            $categoryIdsToUse = (array)$request->subcategories;
        } elseif ($request->has('category_id')) {
            $category = Category::find($request->category_id);
            if ($category) {
                $categoryIdsToUse = $this->getCategoryIds($category);
            }
        } elseif ($request->has('category_ids')) {
            $categoryIdsToUse = (array)$request->category_ids;
        }
        if ($categoryIdsToUse) {
            $query->whereIn('category_id', $categoryIdsToUse);
        }

        // Фильтрация по бренду
        if ($request->has('brands') && !empty($request->brands)) {
            $query->whereIn('brand_id', array_map('intval', (array)$request->brands));
        }

        // Фильтрация по цвету
        if ($request->has('colors') && !empty($request->colors)) {
            $query->whereIn('color_id', array_map('intval', (array)$request->colors));
        }

        // Фильтрация по паттернам
        if ($request->has('patterns') && !empty($request->patterns)) {
            $query->whereIn('pattern', (array)$request->patterns);
        }

        // Фильтрация по текстурам
        if ($request->has('textures') && !empty($request->textures)) {
            $query->whereIn('texture', (array)$request->textures);
        }

        // Фильтрация по стране
        if ($request->has('countries') && !empty($request->countries)) {
            $query->whereIn('country', (array)$request->countries);
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

        // Фильтрация по весу (толщина)
        if ($request->has('weights') && !empty($request->weights)) {
            $attribute = Attribute::where('slug', 'tolshhina')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->weights);
                });
            }
        }

        // Фильтрация по клею
        if ($request->has('glues') && !empty($request->glues)) {
            $attribute = Attribute::where('slug', 'ispolzuetsia-v-kacestve-kleia')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    // Convert "0"/"1" strings back to boolean values for filtering
                    $booleanValues = array_map(function($value) {
                        return $value === '1' || $value === 1 || $value === true || $value === 'true';
                    }, (array)$request->glues);
                    $q->where('attribute_id', $attribute->id)->whereIn('boolean_value', $booleanValues);
                });
            }
        }

        // Фильтрация по типу смеси
        if ($request->has('mixture_types') && !empty($request->mixture_types)) {
            $attribute = Attribute::where('slug', 'tip')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->mixture_types);
                });
            }
        }

        // Фильтрация по шву
        if ($request->has('seams') && !empty($request->seams)) {
            $attribute = Attribute::where('slug', 'sirina-sva')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->seams);
                });
            }
        }

        // Фильтрация по размеру
        if ($request->has('sizes') && !empty($request->sizes)) {
            $attribute = Attribute::where('slug', 'razmery')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->sizes);
                });
            }
        }

        // Фильтрация по материалу
        if ($request->has('materials') && !empty($request->materials)) {
            $attribute = Attribute::where('slug', 'material')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->materials);
                });
            }
        }

        // Фильтрация по влагостойкости
        if ($request->has('waterproofs') && !empty($request->waterproofs)) {
            $attribute = Attribute::where('slug', 'vlagostoikost')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    // Convert "0"/"1" strings back to boolean values for filtering
                    $booleanValues = array_map(function($value) {
                        return $value === '1' || $value === 1 || $value === true || $value === 'true';
                    }, (array)$request->waterproofs);
                    $q->where('attribute_id', $attribute->id)->whereIn('boolean_value', $booleanValues);
                });
            }
        }

        // Фильтрация по коллекции
        if ($request->has('collections') && !empty($request->collections)) {
            $query->whereIn('collection', (array)$request->collections);
        }

        // Фильтрация по объему
        if ($request->has('volumes') && !empty($request->volumes)) {
            $attribute = Attribute::where('slug', 'obem')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->volumes);
                });
            }
        }

        // Фильтрация по весу
        if ($request->has('product_weights') && !empty($request->product_weights)) {
            $attribute = Attribute::where('slug', 'ves')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->product_weights);
                });
            }
        }

        // Фильтрация по типу установки
        if ($request->has('installation_types') && !empty($request->installation_types)) {
            $attribute = Attribute::where('slug', 'tip-ustanovki')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->installation_types);
                });
            }
        }

        // Фильтрация по форме
        if ($request->has('shapes') && !empty($request->shapes)) {
            $attribute = Attribute::where('slug', 'forma')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->shapes);
                });
            }
        }

        // Фильтрация по применению
        if ($request->has('applications') && !empty($request->applications)) {
            $attribute = Attribute::where('slug', 'primenenie')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->applications);
                });
            }
        }

        // Фильтрация по времени высыхания
        if ($request->has('drying_times') && !empty($request->drying_times)) {
            $attribute = Attribute::where('slug', 'vremya-vysykhaniya')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->drying_times);
                });
            }
        }

        // Фильтрация по весу упаковки
        if ($request->has('package_weights') && !empty($request->package_weights)) {
            $attribute = Attribute::where('slug', 'ves-upakovki')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->package_weights);
                });
            }
        }

        // Фильтрация по мин. температуре
        if ($request->has('min_temps') && !empty($request->min_temps)) {
            $attribute = Attribute::where('slug', 'min-temperatura-ekspluatatsii')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->min_temps);
                });
            }
        }

        // Фильтрация по макс. температуре
        if ($request->has('max_temps') && !empty($request->max_temps)) {
            $attribute = Attribute::where('slug', 'maks-temperatura-ekspluatatsii')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->max_temps);
                });
            }
        }

        // Фильтрация по расходу
        if ($request->has('consumptions') && !empty($request->consumptions)) {
            $attribute = Attribute::where('slug', 'rashod')->first();
            if ($attribute) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->consumptions);
                });
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
        $categoryQuery = $this->model->published();
        $baseQuery = clone $categoryQuery;

        // Применяем фильтры категории
        $category = null;
        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);
            if ($category) {
                $categoryIds = $this->getCategoryIds($category);
                $categoryQuery->whereIn('category_id', $categoryIds);
                $baseQuery->whereIn('category_id', $categoryIds);
            }
        } elseif ($request->has('category_ids')) {
            $categoryQuery->whereIn('category_id', (array)$request->category_ids);
            $baseQuery->whereIn('category_id', (array)$request->category_ids);
            // Для получения атрибутов, берем первую категорию из массива
            $categoryId = is_array($request->category_ids) ? $request->category_ids[0] : $request->category_ids;
            $category = Category::find($categoryId);
        }

        // Применяем остальные фильтры к baseQuery для subcategories и counts
        if ($request->has('brands') && !empty($request->brands)) {
            $baseQuery->whereIn('brand_id', array_map('intval', (array)$request->brands));
        }

        if ($request->has('colors') && !empty($request->colors)) {
            $baseQuery->whereIn('color_id', array_map('intval', (array)$request->colors));
        }

        if ($request->has('patterns') && !empty($request->patterns)) {
            $baseQuery->whereIn('pattern', (array)$request->patterns);
        }

        if ($request->has('textures') && !empty($request->textures)) {
            $baseQuery->whereIn('texture', (array)$request->textures);
        }

        if ($request->has('countries') && !empty($request->countries)) {
            $baseQuery->whereIn('country', (array)$request->countries);
        }

        if ($request->has('collections') && !empty($request->collections)) {
            $baseQuery->whereIn('collection', (array)$request->collections);
        }

        // Фильтрация по субкатегориям
        if ($request->has('subcategories') && !empty($request->subcategories)) {
            $baseQuery->whereIn('category_id', array_map('intval', (array)$request->subcategories));
        }

        // Фильтрация по весу (толщина)
        if ($request->has('weights') && !empty($request->weights)) {
            $attribute = Attribute::where('slug', 'tolshhina')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->weights);
                });
            }
        }

        // Фильтрация по клею
        if ($request->has('glues') && !empty($request->glues)) {
            $attribute = Attribute::where('slug', 'ispolzuetsia-v-kacestve-kleia')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    // Convert "0"/"1" strings back to boolean values for filtering
                    $booleanValues = array_map(function($value) {
                        return $value === '1' || $value === 1 || $value === true || $value === 'true';
                    }, (array)$request->glues);
                    $q->where('attribute_id', $attribute->id)->whereIn('boolean_value', $booleanValues);
                });
            }
        }

        // Фильтрация по типу смеси
        if ($request->has('mixture_types') && !empty($request->mixture_types)) {
            $attribute = Attribute::where('slug', 'tip')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->mixture_types);
                });
            }
        }

        // Фильтрация по шву
        if ($request->has('seams') && !empty($request->seams)) {
            $attribute = Attribute::where('slug', 'sirina-sva')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->seams);
                });
            }
        }

        // Фильтрация по размеру
        if ($request->has('sizes') && !empty($request->sizes)) {
            $attribute = Attribute::where('slug', 'razmery')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->sizes);
                });
            }
        }

        if ($request->has('brands') && !empty($request->brands)) {
            $baseQuery->whereIn('brand_id', array_map('intval', (array)$request->brands));
        }

        if ($request->has('colors') && !empty($request->colors)) {
            $baseQuery->whereIn('color_id', array_map('intval', (array)$request->colors));
        }

        if ($request->has('patterns') && !empty($request->patterns)) {
            $baseQuery->whereIn('pattern', (array)$request->patterns);
        }

        if ($request->has('textures') && !empty($request->textures)) {
            $baseQuery->whereIn('texture', (array)$request->textures);
        }

        if ($request->has('collections') && !empty($request->collections)) {
            $baseQuery->whereIn('collection', (array)$request->collections);
        }

        // Фильтрация по субкатегориям
        if ($request->has('subcategories') && !empty($request->subcategories)) {
            $baseQuery->whereIn('category_id', array_map('intval', (array)$request->subcategories));
        }

        // Фильтрация по весу (толщина)
        if ($request->has('weights') && !empty($request->weights)) {
            $attribute = Attribute::where('slug', 'tolshhina')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->weights);
                });
            }
        }

        // Фильтрация по клею
        if ($request->has('glues') && !empty($request->glues)) {
            $attribute = Attribute::where('slug', 'ispolzuetsia-v-kacestve-kleia')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    // Convert "0"/"1" strings back to boolean values for filtering
                    $booleanValues = array_map(function($value) {
                        return $value === '1' || $value === 1 || $value === true || $value === 'true';
                    }, (array)$request->glues);
                    $q->where('attribute_id', $attribute->id)->whereIn('boolean_value', $booleanValues);
                });
            }
        }

        // Фильтрация по типу смеси
        if ($request->has('mixture_types') && !empty($request->mixture_types)) {
            $attribute = Attribute::where('slug', 'tip')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->mixture_types);
                });
            }
        }

        // Фильтрация по шву
        if ($request->has('seams') && !empty($request->seams)) {
            $attribute = Attribute::where('slug', 'sirina-sva')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->seams);
                });
            }
        }

        // Фильтрация по размеру
        if ($request->has('sizes') && !empty($request->sizes)) {
            $attribute = Attribute::where('slug', 'razmery')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->sizes);
                });
            }
        }

        // Фильтрация по материалу
        if ($request->has('materials') && !empty($request->materials)) {
            $attribute = Attribute::where('slug', 'material')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->materials);
                });
            }
        }

        // Фильтрация по влагостойкости
        if ($request->has('waterproofs') && !empty($request->waterproofs)) {
            $attribute = Attribute::where('slug', 'vlagostoikost')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    // Convert "0"/"1" strings back to boolean values for filtering
                    $booleanValues = array_map(function($value) {
                        return $value === '1' || $value === 1 || $value === true || $value === 'true';
                    }, (array)$request->waterproofs);
                    $q->where('attribute_id', $attribute->id)->whereIn('boolean_value', $booleanValues);
                });
            }
        }

        // Фильтрация по коллекции
        if ($request->has('collections') && !empty($request->collections)) {
            $baseQuery->whereIn('collection', (array)$request->collections);
        }

        // Фильтрация по объему
        if ($request->has('volumes') && !empty($request->volumes)) {
            $attribute = Attribute::where('slug', 'obem')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->volumes);
                });
            }
        }

        // Фильтрация по весу
        if ($request->has('product_weights') && !empty($request->product_weights)) {
            $attribute = Attribute::where('slug', 'ves')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->product_weights);
                });
            }
        }

        // Фильтрация по типу установки
        if ($request->has('installation_types') && !empty($request->installation_types)) {
            $attribute = Attribute::where('slug', 'tip-ustanovki')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->installation_types);
                });
            }
        }

        // Фильтрация по форме
        if ($request->has('shapes') && !empty($request->shapes)) {
            $attribute = Attribute::where('slug', 'forma')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->shapes);
                });
            }
        }

        // Фильтрация по применению
        if ($request->has('applications') && !empty($request->applications)) {
            $attribute = Attribute::where('slug', 'primenenie')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->applications);
                });
            }
        }

        // Фильтрация по времени высыхания
        if ($request->has('drying_times') && !empty($request->drying_times)) {
            $attribute = Attribute::where('slug', 'vremya-vysykhaniya')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->drying_times);
                });
            }
        }

        // Фильтрация по весу упаковки
        if ($request->has('package_weights') && !empty($request->package_weights)) {
            $attribute = Attribute::where('slug', 'ves-upakovki')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->package_weights);
                });
            }
        }

        // Фильтрация по мин. температуре
        if ($request->has('min_temps') && !empty($request->min_temps)) {
            $attribute = Attribute::where('slug', 'min-temperatura-ekspluatatsii')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->min_temps);
                });
            }
        }

        // Фильтрация по макс. температуре
        if ($request->has('max_temps') && !empty($request->max_temps)) {
            $attribute = Attribute::where('slug', 'maks-temperatura-ekspluatatsii')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('number_value', (array)$request->max_temps);
                });
            }
        }

        // Фильтрация по расходу
        if ($request->has('consumptions') && !empty($request->consumptions)) {
            $attribute = Attribute::where('slug', 'rashod')->first();
            if ($attribute) {
                $baseQuery->whereHas('attributeValues', function ($q) use ($attribute, $request) {
                    $q->where('attribute_id', $attribute->id)->whereIn('string_value', (array)$request->consumptions);
                });
            }
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

        $countriesQuery = clone $baseQuery;
        $countries = $countriesQuery->select('country as value', DB::raw('COUNT(*) as count'))
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->groupBy('country')
            ->get()
            ->map(fn($item) => ['id' => $item->value, 'name' => $item->value, 'count' => $item->count])
            ->toArray();

        $collectionsQuery = clone $baseQuery;
        $collections = $collectionsQuery->select('collection as value', DB::raw('COUNT(*) as count'))
            ->whereNotNull('collection')
            ->where('collection', '!=', '')
            ->groupBy('collection')
            ->get()
            ->map(fn($item) => ['id' => $item->value, 'name' => $item->value, 'count' => $item->count])
            ->toArray();

        // Инициализируем массивы для атрибутов
        $weights = [];
        $glues = [];
        $mixture_types = [];
        $seams = [];
        $sizes = [];
        $materials = [];
        $waterproofs = [];
        $volumes = [];
        $product_weights = [];
        $installation_types = [];
        $shapes = [];
        $applications = [];
        $drying_times = [];
        $package_weights = [];
        $min_temps = [];
        $max_temps = [];
        $consumptions = [];

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
                    case 'ispolzuetsia-v-kacestve-kleia':
                        // Convert boolean values to "Да"/"Нет" strings for display but keep ID as boolean
                        $glues = array_map(function($item) {
                            if ($item['value'] === '1' || $item['value'] === 1 || $item['value'] === true) {
                                $item['name'] = 'Да';
                                $item['id'] = true;
                            } elseif ($item['value'] === '0' || $item['value'] === 0 || $item['value'] === false) {
                                $item['name'] = 'Нет';
                                $item['id'] = false;
                            }
                            return $item;
                        }, $values);
                        break;
                    case 'tip':
                        $mixture_types = $values; // тип смеси или тип для сантехники
                        break;
                    case 'sirina-sva':
                        $seams = $values;
                        break;
                    case 'razmery':
                        $sizes = $values;
                        break;
                    case 'material':
                        $materials = $values;
                        break;
                    case 'vlagostoikost':
                        // Convert boolean values to "Да"/"Нет" strings for display but keep ID as boolean
                        $waterproofs = array_map(function($item) {
                            if ($item['value'] === '1' || $item['value'] === 1 || $item['value'] === true) {
                                $item['name'] = 'Да';
                                $item['id'] = true;
                            } elseif ($item['value'] === '0' || $item['value'] === 0 || $item['value'] === false) {
                                $item['name'] = 'Нет';
                                $item['id'] = false;
                            }
                            return $item;
                        }, $values);
                        break;
                    case 'obem':
                        $volumes = $values;
                        break;
                    case 'ves':
                        $product_weights = $values;
                        break;
                    case 'tip-ustanovki':
                        $installation_types = $values;
                        break;
                    case 'forma':
                        $shapes = $values;
                        break;
                    case 'primenenie':
                        $applications = $values;
                        break;
                    case 'vremya-vysykhaniya':
                        $drying_times = $values;
                        break;
                    case 'ves-upakovki':
                        $package_weights = $values;
                        break;
                    case 'min-temperatura-ekspluatatsii':
                        $min_temps = $values;
                        break;
                    case 'maks-temperatura-ekspluatatsii':
                        $max_temps = $values;
                        break;
                    case 'rashod':
                        $consumptions = $values;
                        break;
                }
            }
        }

        // Дополнительно собираем sizes, если не собраны из filterableAttributes
        if (empty($sizes)) {
            $sizesQuery = DB::table('product_attribute_values')
                ->join('products', 'product_attribute_values.product_id', '=', 'products.id')
                ->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                ->where('attributes.slug', 'razmery')
                ->whereIn('products.id', $baseQuery->select('id'))
                ->where('products.is_published', true)
                ->select('product_attribute_values.string_value as value', DB::raw('COUNT(*) as count'))
                ->whereNotNull('product_attribute_values.string_value')
                ->groupBy('product_attribute_values.string_value')
                ->get()
                ->map(function ($item) {
                    return ['id' => $item->value, 'name' => $item->value, 'count' => $item->count];
                })
                ->toArray();
            $sizes = $sizesQuery;
        }

        // Дополнительно собираем glues, если не собраны из filterableAttributes
        if (empty($glues)) {
            $gluesQuery = DB::table('product_attribute_values')
                ->join('products', 'product_attribute_values.product_id', '=', 'products.id')
                ->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                ->where('attributes.slug', 'ispolzuetsia-v-kacestve-kleia')
                ->whereIn('products.id', $baseQuery->select('id'))
                ->where('products.is_published', true)
                ->select('product_attribute_values.boolean_value as value', DB::raw('COUNT(*) as count'))
                ->whereNotNull('product_attribute_values.boolean_value')
                ->groupBy('product_attribute_values.boolean_value')
                ->get()
                ->map(function ($item) {
                    $name = ($item->value == 1 || $item->value === true || $item->value === 'true') ? 'Да' : 'Нет';
                    $id = ($item->value == 1 || $item->value === true || $item->value === 'true') ? true : false;
                    return ['id' => $id, 'name' => $name, 'count' => $item->count];
                })
                ->toArray();
            $glues = $gluesQuery;
        }

        // Дополнительно собираем waterproofs, если не собраны из filterableAttributes
        if (empty($waterproofs)) {
            $waterproofsQuery = DB::table('product_attribute_values')
                ->join('products', 'product_attribute_values.product_id', '=', 'products.id')
                ->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                ->where('attributes.slug', 'vlagostoikost')
                ->whereIn('products.id', $baseQuery->select('id'))
                ->where('products.is_published', true)
                ->select('product_attribute_values.boolean_value as value', DB::raw('COUNT(*) as count'))
                ->whereNotNull('product_attribute_values.boolean_value')
                ->groupBy('product_attribute_values.boolean_value')
                ->get()
                ->map(function ($item) {
                    $name = ($item->value == 1 || $item->value === true || $item->value === 'true') ? 'Да' : 'Нет';
                    $id = ($item->value == 1 || $item->value === true || $item->value === 'true') ? true : false;
                    return ['id' => $id, 'name' => $name, 'count' => $item->count];
                })
                ->toArray();
            $waterproofs = $waterproofsQuery;
        }

        // Ценовой диапазон
        $priceRange = [
            'min' => $baseQuery->min('price'),
            'max' => $baseQuery->max('price')
        ];

        return [
            'brands' => $brands,
            'colors' => $colors,
            'patterns' => $patterns,
            'weights' => $weights,
            'glues' => $glues,
            'mixture_types' => $mixture_types,
            'seams' => $seams,
            'textures' => $textures,
            'countries' => $countries,
            'sizes' => $sizes,
            'materials' => $materials,
            'waterproofs' => $waterproofs,
            'collections' => $collections,
            'volumes' => $volumes,
            'product_weights' => $product_weights,
            'installation_types' => $installation_types,
            'shapes' => $shapes,
            'applications' => $applications,
            'drying_times' => $drying_times,
            'package_weights' => $package_weights,
            'min_temps' => $min_temps,
            'max_temps' => $max_temps,
            'consumptions' => $consumptions,
            'price_range' => $priceRange,
            'has_sale' => $baseQuery->where('is_sale', true)->exists()
        ];
    }
}
