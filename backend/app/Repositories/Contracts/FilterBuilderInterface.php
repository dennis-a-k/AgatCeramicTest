<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface FilterBuilderInterface
{
    public function buildBaseQuery(Request $request): Builder;
    public function applyBaseFilters(Builder $query, Request $request): void;
    public function applyFilters(Request $request): \Illuminate\Pagination\LengthAwarePaginator;
}