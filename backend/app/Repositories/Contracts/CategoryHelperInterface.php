<?php

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

interface CategoryHelperInterface
{
    public function getCategoryIds(Category $category): array;
    public function getSubcategories(Builder $baseQuery, Category $category): array;
}