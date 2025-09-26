<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class FilterRepository
{
    protected $model;

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

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * Получение ID категорий, включая дочерние
     */
    public function getCategoryIds(Category $category): array
    {
        return $category->children->pluck('id')->push($category->id)->toArray();
    }

    /**
     * Применение базовых фильтров к запросу
     */
    protected function applyBaseFilters($query, Request $request)
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
        $query = $this->model->with(['category', 'brand', 'color', 'attributeValues.attribute'])->published();

        $this->applyBaseFilters($query, $request);

        // Сортировка
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

        // Пагинация
        $perPage = $request->get('per_page', 40);
        return $query->paginate($perPage);
    }

    /**
     * Получение доступных фильтров
     */
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
            $categoryId = is_array($request->category_ids) ? $request->category_ids[0] : $request->category_ids;
            $category = Category::find($categoryId);
        }

        // Применяем остальные фильтры к baseQuery
        $this->applyBaseFilters($baseQuery, $request);

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

        // Получаем значения атрибутов
        $attributeValues = $this->getAttributeValues($baseQuery, $category);

        // Ценовой диапазон
        $priceRange = [
            'min' => $baseQuery->min('price'),
            'max' => $baseQuery->max('price')
        ];

        return array_merge([
            'brands' => $brands,
            'colors' => $colors,
            'patterns' => $patterns,
            'textures' => $textures,
            'countries' => $countries,
            'collections' => $collections,
            'price_range' => $priceRange,
            'has_sale' => $baseQuery->where('is_sale', true)->exists()
        ], $attributeValues);
    }

    /**
     * Получение значений атрибутов
     */
    protected function getAttributeValues($baseQuery, $category): array
    {
        $result = [];

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

                // Преобразование для boolean атрибутов
                if ($attribute->type === 'boolean') {
                    $values = array_map(function($item) {
                        if ($item['value'] === '1' || $item['value'] === 1 || $item['value'] === true) {
                            $item['name'] = 'Да';
                            $item['id'] = true;
                        } elseif ($item['value'] === '0' || $item['value'] === 0 || $item['value'] === false) {
                            $item['name'] = 'Нет';
                            $item['id'] = false;
                        }
                        return $item;
                    }, $values);
                }

                // Распределяем по ключам
                $key = array_search($attribute->slug, array_column($this->attributeConfigs, 'slug'));
                if ($key !== false) {
                    $result[array_keys($this->attributeConfigs)[$key]] = $values;
                }
            }
        }

        // Дополнительно собираем sizes, glues, waterproofs если не собраны
        $additionalAttributes = ['sizes', 'glues', 'waterproofs'];
        foreach ($additionalAttributes as $attrKey) {
            if (empty($result[$attrKey])) {
                $config = $this->attributeConfigs[$attrKey];
                $valuesQuery = DB::table('product_attribute_values')
                    ->join('products', 'product_attribute_values.product_id', '=', 'products.id')
                    ->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                    ->where('attributes.slug', $config['slug'])
                    ->whereIn('products.id', $baseQuery->select('id'))
                    ->where('products.is_published', true)
                    ->select($config['field'] . ' as value', DB::raw('COUNT(*) as count'))
                    ->whereNotNull($config['field'])
                    ->groupBy($config['field'])
                    ->get()
                    ->map(function ($item) use ($config) {
                        $name = $item->value;
                        $id = $item->value;
                        if ($config['type'] === 'boolean') {
                            $name = ($item->value == 1 || $item->value === true || $item->value === 'true') ? 'Да' : 'Нет';
                            $id = ($item->value == 1 || $item->value === true || $item->value === 'true') ? true : false;
                        }
                        return ['id' => $id, 'name' => $name, 'count' => $item->count];
                    })
                    ->toArray();
                $result[$attrKey] = $valuesQuery;
            }
        }

        // Инициализируем пустые массивы для всех атрибутов
        foreach (array_keys($this->attributeConfigs) as $key) {
            if (!isset($result[$key])) {
                $result[$key] = [];
            }
        }

        return $result;
    }
}
