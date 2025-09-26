<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\FilterableRepositoryInterface;
use App\Repositories\Contracts\FilterBuilderInterface;
use App\Repositories\Contracts\AvailableFiltersInterface;
use App\Repositories\Contracts\CategoryHelperInterface;

class FilterRepository implements FilterableRepositoryInterface
{
    protected $filterBuilder;
    protected $availableFiltersRetriever;
    protected $categoryHelper;

    public function __construct(
        FilterBuilderInterface $filterBuilder,
        AvailableFiltersInterface $availableFiltersRetriever,
        CategoryHelperInterface $categoryHelper
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->availableFiltersRetriever = $availableFiltersRetriever;
        $this->categoryHelper = $categoryHelper;
    }

    // Реализация RepositoryInterface (пустые методы, так как не используются в фильтрах)
    public function all() {}
    public function find($id) {}
    public function create(array $data) {}
    public function update($id, array $data) {}
    public function delete($id) {}

    // Делегирование методов FilterBuilderInterface
    public function buildBaseQuery(Request $request): Builder
    {
        return $this->filterBuilder->buildBaseQuery($request);
    }

    public function applyBaseFilters(Builder $query, Request $request): void
    {
        $this->filterBuilder->applyBaseFilters($query, $request);
    }

    public function applyFilters(Request $request): LengthAwarePaginator
    {
        return $this->filterBuilder->applyFilters($request);
    }

    // Делегирование методов AvailableFiltersInterface
    public function getAvailableFilters(Request $request): array
    {
        return $this->availableFiltersRetriever->getAvailableFilters($request);
    }

    public function getAvailableFiltersForQuery(Builder $baseQuery, $category = null): array
    {
        return $this->availableFiltersRetriever->getAvailableFiltersForQuery($baseQuery, $category);
    }

    public function getFilteredQueryAndFilters(Request $request): array
    {
        return $this->availableFiltersRetriever->getFilteredQueryAndFilters($request);
    }

    // Делегирование методов CategoryHelperInterface
    public function getCategoryIds(Category $category): array
    {
        return $this->categoryHelper->getCategoryIds($category);
    }

    public function getSubcategories(Builder $baseQuery, Category $category): array
    {
        return $this->categoryHelper->getSubcategories($baseQuery, $category);
    }
}
