<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class NumberFilter implements FilterInterface
{
    public function apply(Builder $query, $value): void
    {
        if (is_array($value) && count($value) === 2) {
            $query->whereBetween('number_value', $value);
        } else {
            $query->where('number_value', $value);
        }
    }

    public function supports(string $type): bool
    {
        return $type === 'number';
    }
}
