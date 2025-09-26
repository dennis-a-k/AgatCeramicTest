<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\CategoryHelperInterface;

class CategoryHelper implements CategoryHelperInterface
{
    /**
     * Получение ID категорий, включая дочерние
     */
    public function getCategoryIds(Category $category): array
    {
        return $category->children->pluck('id')->push($category->id)->toArray();
    }

    /**
     * Получение подкатегорий с количеством продуктов
     */
    public function getSubcategories(Builder $baseQuery, Category $category): array
    {
        return $category->children()->whereHas('products', function ($q) use ($baseQuery) {
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
    }
}
