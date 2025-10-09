<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Repositories\Contracts\FilterableRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
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
        return $this->model->with(['category', 'brand', 'color', 'attributeValues.attribute', 'images'])
            ->find($id);
    }

    public function findBySlug($slug): ?Product
    {
        return $this->model->with(['category', 'brand', 'color', 'attributeValues.attribute'])
            ->published()
            ->where('slug', $slug)
            ->first();
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

    /**
     * Получение товаров на распродаже с фильтрами
     *
     * @param Request $request Параметры фильтрации
     * @return array
     */
    public function getBySale(Request $request): array
    {
        // Создаем клон запроса с добавленным фильтром по распродаже
        $newRequest = clone $request;
        $newRequest->merge(['is_sale' => true]);

        $products = $this->filterRepository->applyFilters($newRequest);

        $filteredData = $this->filterRepository->getFilteredQueryAndFilters($newRequest);
        $filters = $filteredData['filters'];
        $baseQuery = $filteredData['query'];

        // Для распродажи subcategories не нужны, так как нет конкретной категории

        return [
            'products' => $products,
            'filters' => $filters
        ];
    }

    /**
     * Получение товаров по slug бренда с фильтрами
     *
     * @param string $slug Slug бренда
     * @param Request $request Параметры фильтрации
     * @return array
     */
    public function getByBrandSlug($slug, Request $request): array
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        // Создаем клон запроса с добавленным brand_ids
        $newRequest = clone $request;
        $newRequest->merge(['brands' => [$brand->id]]);

        $products = $this->filterRepository->applyFilters($newRequest);

        $filteredData = $this->filterRepository->getFilteredQueryAndFilters($newRequest);
        $filters = $filteredData['filters'];
        $baseQuery = $filteredData['query'];

        return [
            'brand' => $brand,
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

    public function buildBaseQuery(Request $request): Builder
    {
        return $this->filterRepository->buildBaseQuery($request);
    }

    public function applyBaseFilters(Builder $query, Request $request): void
    {
        $this->filterRepository->applyBaseFilters($query, $request);
    }

    public function getAvailableFiltersForQuery(Builder $baseQuery, $category = null): array
    {
        return $this->filterRepository->getAvailableFiltersForQuery($baseQuery, $category);
    }

    public function getFilteredQueryAndFilters(Request $request): array
    {
        return $this->filterRepository->getFilteredQueryAndFilters($request);
    }

    public function getCategoryIds(Category $category): array
    {
        return $this->filterRepository->getCategoryIds($category);
    }

    public function getSubcategories(Builder $baseQuery, Category $category): array
    {
        return $this->filterRepository->getSubcategories($baseQuery, $category);
    }

}
