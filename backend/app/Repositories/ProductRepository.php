<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductFilterService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function __construct(private ProductFilterService $filterService) {}

    public function getFilteredProducts(array $filters, ?Category $category = null): LengthAwarePaginator
    {
        $query = Product::with(['category', 'brand', 'attributeValues.attribute'])
            ->where('is_published', true);

        if ($category) {
            $query->where('category_id', $category->id);
        }

        // Базовые фильтры
        if (isset($filters['brand'])) {
            $query->whereIn('brand_id', (array)$filters['brand']);
        }

        if (isset($filters['price_min'])) {
            $query->where('price', '>=', $filters['price_min']);
        }

        if (isset($filters['price_max'])) {
            $query->where('price', '<=', $filters['price_max']);
        }

        if (isset($filters['is_sale'])) {
            $query->where('is_sale', (bool)$filters['is_sale']);
        }

        // Поиск
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('article', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('product_code', 'LIKE', "%{$search}%");
            });
        }

        // Фильтры по атрибутам
        $attributeFilters = array_diff_key($filters, array_flip([
            'brand',
            'price_min',
            'price_max',
            'is_sale',
            'search',
            'sort',
            'page'
        ]));

        $query = $this->filterService->applyFilters($query, $attributeFilters, $category);
        $query = $this->applySorting($query, $filters['sort'] ?? null);

        return $query->paginate(24)->appends($filters);
    }

    private function applySorting(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc')
        };
    }
}
