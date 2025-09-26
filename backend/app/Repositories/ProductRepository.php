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

        // Создаем клон запроса с добавленным category_ids
        $newRequest = clone $request;
        $newRequest->merge(['category_ids' => $categoryIds]);

        $products = $this->filterRepository->applyFilters($newRequest);

        $filteredData = $this->filterRepository->getFilteredQueryAndFilters($newRequest);
        $filters = $filteredData['filters'];
        $baseQuery = $filteredData['query'];

        // Добавляем subcategories
        $filters['subcategories'] = $this->filterRepository->getSubcategories($baseQuery, $category);

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
