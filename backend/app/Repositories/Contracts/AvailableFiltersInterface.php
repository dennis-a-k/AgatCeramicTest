<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface AvailableFiltersInterface
{
    public function getAvailableFilters(Request $request): array;
    public function getAvailableFiltersForQuery(Builder $baseQuery, $category = null): array;
    public function getFilteredQueryAndFilters(Request $request): array;
}