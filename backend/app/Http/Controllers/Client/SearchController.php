<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Поиск товаров по article, name и product_code
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2|max:255',
        ]);

        $result = $this->productService->getProductsWithFilters($request->merge(['search' => $request->query]));

        return response()->json([
            'products' => $result['products'] ?? [],
            'filters' => $result['filters'] ?? [],
            'meta' => [
                'total' => $result['products']->total() ?? 0,
                'current_page' => $result['products']->currentPage() ?? 1,
                'per_page' => $result['products']->perPage() ?? 40,
                'last_page' => $result['products']->lastPage() ?? 1,
            ]
        ]);
    }

    /**
     * Быстрый поиск для автодополнения
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function quickSearch(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100',
        ]);

        $products = $this->productService->quickSearch($request->input('query'));

        return response()->json([
            'results' => $products,
            'total' => count($products)
        ]);
    }
}
