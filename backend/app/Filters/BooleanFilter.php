<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class BooleanFilter implements FilterInterface
{
    public function apply(Builder $query, $value): void
    {
        $query->where('boolean_value', (bool)$value);
    }

    public function supports(string $type): bool
    {
        return $type === 'boolean';
    }
}
