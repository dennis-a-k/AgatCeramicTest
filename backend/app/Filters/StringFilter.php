<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class StringFilter implements FilterInterface
{
    public function apply(Builder $query, $value): void
    {
        if (is_array($value)) {
            $query->whereIn('string_value', $value);
        } else {
            $query->where('string_value', $value);
        }
    }

    public function supports(string $type): bool
    {
        return $type === 'string';
    }
}
