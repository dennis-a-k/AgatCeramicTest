<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\AvailableFiltersInterface;
use App\Repositories\Contracts\FilterBuilderInterface;

class AvailableFiltersRetriever implements AvailableFiltersInterface
{
    protected $filterBuilder;

    // Конфигурация атрибутов для фильтрации
    protected $attributeConfigs = [
        'weights' => ['slug' => 'tolshhina', 'type' => 'number', 'field' => 'number_value'],
        'glues' => ['slug' => 'ispolzuetsia-v-kacestve-kleia', 'type' => 'boolean', 'field' => 'boolean_value'],
        'mixture_types' => ['slug' => 'tip', 'type' => 'string', 'field' => 'string_value'],
        'seams' => ['slug' => 'sirina-sva', 'type' => 'string', 'field' => 'string_value'],
        'sizes' => ['slug' => 'gabarity', 'type' => 'string', 'field' => 'string_value'],
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

    public function __construct(FilterBuilderInterface $filterBuilder)
    {
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Получение доступных фильтров
     */
    public function getAvailableFilters(Request $request): array
    {
        $baseQuery = $this->filterBuilder->buildBaseQuery($request);

        // Определяем категорию для атрибутов
        $category = null;
        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);
        } elseif ($request->has('category_ids')) {
            $categoryId = is_array($request->category_ids) ? $request->category_ids[0] : $request->category_ids;
            $category = Category::find($categoryId);
        }

        return $this->getAvailableFiltersForQuery($baseQuery, $category);
    }

    /**
     * Получение отфильтрованного запроса и доступных фильтров
     */
    public function getFilteredQueryAndFilters(Request $request): array
    {
        $baseQuery = $this->filterBuilder->buildBaseQuery($request);

        // Определяем категорию для атрибутов
        $category = null;
        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);
        } elseif ($request->has('category_ids')) {
            $categoryId = is_array($request->category_ids) ? $request->category_ids[0] : $request->category_ids;
            $category = Category::find($categoryId);
        }

        $filters = $this->getAvailableFiltersForQuery($baseQuery, $category);

        return [
            'query' => $baseQuery,
            'filters' => $filters
        ];
    }

    /**
     * Получение доступных фильтров для заданного запроса
     */
    public function getAvailableFiltersForQuery(Builder $baseQuery, $category = null): array
    {
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

        // Получаем доступные категории
        $categories = Category::whereHas('products', function ($q) use ($baseQuery) {
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
            'categories' => $categories,
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
    protected function getAttributeValues(Builder $baseQuery, $category): array
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

        // Всегда собираем дополнительные атрибуты, перезаписывая если необходимо
        $additionalAttributes = ['sizes', 'glues', 'waterproofs', 'weights', 'seams', 'product_weights', 'installation_types', 'shapes'];
        foreach ($additionalAttributes as $attrKey) {
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

        // Инициализируем пустые массивы для всех атрибутов
        foreach (array_keys($this->attributeConfigs) as $key) {
            if (!isset($result[$key])) {
                $result[$key] = [];
            }
        }

        return $result;
    }
}
