<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected $productService;
    protected $searchService;

    public function __construct(ProductService $productService, SearchService $searchService)
    {
        $this->productService = $productService;
        $this->searchService = $searchService;
    }

    public function index(Request $request): JsonResponse
    {
        $result = $this->productService->getProductsWithFilters($request);
        return response()->json($result);
    }

    public function show($id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    /**
     * Получение товаров по категории с фильтрами
     *
     * @param string $slug Slug категории
     * @param Request $request Параметры фильтрации
     * @return JsonResponse
     */
    public function getByCategory($slug, Request $request): JsonResponse
    {
        $result = $this->searchService->getProductsByCategory($slug, $request);
        return response()->json($result);
    }

    /**
     * Получение товаров на распродаже с фильтрами
     *
     * @param Request $request Параметры фильтрации
     * @return JsonResponse
     */
    public function getBySale(Request $request): JsonResponse
    {
        $result = $this->searchService->getProductsBySale($request);
        return response()->json($result);
    }
}
