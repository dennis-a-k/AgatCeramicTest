<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\FilterBuilderInterface;
use App\Repositories\Contracts\CategoryHelperInterface;

class FilterBuilder implements FilterBuilderInterface
{
    protected $model;
    protected $categoryHelper;

    // Конфигурация атрибутов для фильтрации
    protected $attributeConfigs = [
        'weights' => ['slug' => 'tolshhina', 'type' => 'number', 'field' => 'number_value'],
        'glues' => ['slug' => 'ispolzuetsia-v-kacestve-kleia', 'type' => 'boolean', 'field' => 'boolean_value'],
        'mixture_types' => ['slug' => 'tip', 'type' => 'string', 'field' => 'string_value'],
        'seams' => ['slug' => 'sirina-sva', 'type' => 'string', 'field' => 'string_value'],
        'sizes' => ['slug' => 'razmery', 'type' => 'string', 'field' => 'string_value'],
        'materials' => ['slug' => 'material', 'type' => 'string', 'field' => 'string_value'],
        'waterproofs' => ['slug' => 'vlagostoikost', 'type' => 'boolean', 'field' => 'boolean_value'],
        'volumes' => ['slug' => 'obem', 'type' => 'number', 'field' => 'number_value'],
        'product_weights' => ['slug' => 'ves', 'type' => 'number', 'field' => 'number_value'],
        'installation_types' => ['slug' => 'tip-ustanovki', 'type' => 'string', 'field' => 'string_value'],
        'shapes' => ['slug' => 'forma', 'type' => 'string', 'field' => 'string_value'],
        'applications' => ['slug' => 'primenenie', 'type' => 'string', 'field' => 'string_value'],
        'drying_times' => ['slug' => 'vremya-vysykhaniya', 'type' => 'string', 'field' => 'string_value'],
        'package_weights' => ['slug' => 'ves-upakovki', 'type' => 'number', 'field' => 'number_value'],
        'min_temps' => ['slug' => 'min-temperatura-ekspluatatsii', 'type' => 'number', 'field' => 'number_value'],
        'max_temps' => ['slug' => 'maks-temperatura-ekspluatatsii', 'type' => 'number', 'field' => 'number_value'],
        'consumptions' => ['slug' => 'rashod', 'type' => 'string', 'field' => 'string_value'],
    ];

    public function __construct(Product $model, CategoryHelperInterface $categoryHelper)
    {
        $this->model = $model;
        $this->categoryHelper = $categoryHelper;
    }

    /**
     * Построение базового запроса с примененными фильтрами
     */
    public function buildBaseQuery(Request $request): Builder
    {
        $query = $request->has('admin') && $request->admin ? $this->model->query() : $this->model->published();
        $this->applyBaseFilters($query, $request);
        return $query;
    }

    /**
     * Применение базовых фильтров к запросу
     */
    public function applyBaseFilters(Builder $query, Request $request): void
    {
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
                $categoryIdsToUse = $this->categoryHelper->getCategoryIds($category);
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
            $saleValues = array_map(function($value) {
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }, (array)$request->is_sale);
            if (!empty($saleValues)) {
                $query->whereIn('is_sale', $saleValues);
            }
        }

        // Фильтрация по публикации
        if ($request->has('is_published')) {
            $publishedValues = array_map(function($value) {
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }, (array)$request->is_published);
            if (!empty($publishedValues)) {
                $query->whereIn('is_published', $publishedValues);
            }
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

        // Фильтрация по коллекции
        if ($request->has('collections') && !empty($request->collections)) {
            $query->whereIn('collection', (array)$request->collections);
        }

        // Применение фильтров по атрибутам из конфигурации
        foreach ($this->attributeConfigs as $key => $config) {
            if ($request->has($key) && !empty($request->$key)) {
                $attribute = Attribute::where('slug', $config['slug'])->first();
                if ($attribute) {
                    $query->whereHas('attributeValues', function ($q) use ($attribute, $request, $key, $config) {
                        $q->where('attribute_id', $attribute->id);
                        if ($config['type'] === 'boolean') {
                            $booleanValues = array_map(function($value) {
                                return $value === '1' || $value === 1 || $value === true || $value === 'true';
                            }, (array)$request->$key);
                            $q->whereIn($config['field'], $booleanValues);
                        } elseif ($config['type'] === 'number') {
                            $q->whereIn($config['field'], (array)$request->$key);
                        } else {
                            $q->whereIn($config['field'], (array)$request->$key);
                        }
                    });
                }
            }
        }
    }

    /**
     * Применение фильтров с сортировкой и пагинацией
     */
    public function applyFilters(Request $request): LengthAwarePaginator
    {
        $query = $this->buildBaseQuery($request)->with(['category', 'brand', 'color', 'attributeValues.attribute']);

        // Сортировка
        if ($request->has('sort_key') && $request->has('sort_direction')) {
            $query->orderBy($request->sort_key, $request->sort_direction);
        } else {
            $sortOption = $request->get('sort', 'default');

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
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        // Пагинация
        $perPage = $request->get('per_page', 40);
        return $query->paginate($perPage);
    }
}
