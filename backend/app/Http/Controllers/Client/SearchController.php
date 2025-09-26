<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    protected $productService;
    protected $searchService;

    public function __construct(ProductService $productService, SearchService $searchService)
    {
        $this->productService = $productService;
        $this->searchService = $searchService;
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

        $result = $this->productService->getProductsWithFilters($request->merge(['search' => $request->input('query')]));

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

        $products = $this->searchService->quickSearch($request->input('query'));

        return response()->json([
            'results' => $products,
            'total' => count($products)
        ]);
    }
}
