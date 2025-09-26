<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Repositories\Contracts\FilterableRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements FilterableRepositoryInterface
{
    protected $model;
    protected $filterRepository;

    public function __construct(Product $model, FilterRepository $filterRepository)
    {
        $this->model = $model;
        $this->filterRepository = $filterRepository;
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
     * Получение товаров по slug категории с фильтрами
     *
     * @param string $slug Slug категории
     * @param Request $request Параметры фильтрации
     * @return array
     */
    public function getByCategorySlug($slug, Request $request): array
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $categoryIds = $this->filterRepository->getCategoryIds($category);

        // Создаем клон запроса с добавленным category_id
        $newRequest = clone $request;
        $newRequest->merge(['category_ids' => $categoryIds]);

        $products = $this->filterRepository->applyFilters($newRequest);
        $filters = $this->filterRepository->getAvailableFilters($newRequest);

        // Строим baseQuery для subcategories
        $baseQuery = $this->model->published();
        if ($request->has('category_id')) {
            $cat = Category::find($request->category_id);
            if ($cat) {
                $categoryIds = $this->filterRepository->getCategoryIds($cat);
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


    public function applyFilters(Request $request): LengthAwarePaginator
    {
        return $this->filterRepository->applyFilters($request);
    }

    public function getAvailableFilters(Request $request): array
    {
        return $this->filterRepository->getAvailableFilters($request);
    }

}
