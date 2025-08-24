<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\Category;

class FilterOptionsService
{
    public function getAvailableFilters(?Category $category): array
    {
        if (!$category) {
            return [];
        }

        $attributes = Attribute::with(['values' => function ($query) use ($category) {
            $this->applyProductConstraints($query, $category);
        }])
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('id', $category->id)
                    ->where('is_filterable', true);
            })
            ->orderBy('name')
            ->get();

        $filters = [];

        foreach ($attributes as $attribute) {
            $values = $this->extractValues($attribute);

            if (!empty($values)) {
                $filters[$attribute->slug] = [
                    'name' => $attribute->name,
                    'type' => $attribute->type,
                    'values' => $values
                ];
            }
        }

        return $filters;
    }

    private function applyProductConstraints($query, Category $category): void
    {
        $query->whereHas('product', function ($q) use ($category) {
            $q->where('is_published', true)
                ->where('category_id', $category->id);
        });
    }

    private function extractValues(Attribute $attribute): array
    {
        return $attribute->values
            ->map(function ($value) use ($attribute) {
                return match ($attribute->type) {
                    'number' => (float)$value->number_value,
                    'boolean' => (bool)$value->boolean_value,
                    default => (string)$value->string_value
                };
            })
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }
}
