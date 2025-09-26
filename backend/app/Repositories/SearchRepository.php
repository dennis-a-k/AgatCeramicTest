<?php

namespace App\Repositories;

use App\Models\Product;

class SearchRepository
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * Быстрый поиск для автодополнения
     *
     * @param string $query
     * @return array
     */
    public function quickSearch(string $query): array
    {
        return $this->model->published()
            ->where(function ($q) use ($query) {
                $q->where('article', 'like', "%{$query}%")
                  ->orWhere('name', 'like', "%{$query}%")
                  ->orWhere('product_code', 'like', "%{$query}%");
            })
            ->select('id', 'article', 'name', 'product_code', 'slug')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'article' => $product->article,
                    'name' => $product->name,
                    'product_code' => $product->product_code,
                    'slug' => $product->slug,
                    'display_text' => "{$product->article} - {$product->name} ({$product->product_code})"
                ];
            })
            ->toArray();
    }
}
