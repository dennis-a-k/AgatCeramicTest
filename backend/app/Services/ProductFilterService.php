<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\Category;
use App\Filters\FilterInterface;
use Illuminate\Database\Eloquent\Builder;

class ProductFilterService
{
    private array $filters;

    public function __construct(FilterInterface ...$filters)
    {
        $this->filters = $filters;
    }

    public function applyFilters(Builder $query, array $filters, ?Category $category = null): Builder
    {
        foreach ($filters as $attributeSlug => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $attribute = Attribute::where('slug', $attributeSlug)->first();

            if ($attribute && $this->isFilterableForCategory($attribute, $category)) {
                $this->applyAttributeFilter($query, $attribute, $value);
            }
        }

        return $query;
    }

    private function applyAttributeFilter(Builder $query, Attribute $attribute, $value): void
    {
        foreach ($this->filters as $filter) {
            if ($filter->supports($attribute->type)) {
                $query->whereHas('attributeValues', function ($q) use ($filter, $attribute, $value) {
                    $q->where('attribute_id', $attribute->id);
                    $filter->apply($q, $value);
                });
                break;
            }
        }
    }

    private function isFilterableForCategory(Attribute $attribute, ?Category $category): bool
    {
        if (!$category) {
            return true;
        }

        return $attribute->categories()
            ->where('category_id', $category->id)
            ->where('is_filterable', true)
            ->exists();
    }
}
