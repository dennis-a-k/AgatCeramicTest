<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductListRequest;
use App\Models\Category;
use App\Repositories\ProductRepository;
use App\Services\FilterOptionsService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private ProductRepository $productRepository,
        private FilterOptionsService $optionsService
    ) {}

    public function index(ProductListRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $products = $this->productRepository->getFilteredProducts($filters);

        return response()->json([
            'products' => $products,
            'filters' => []
        ]);
    }

    public function byCategory(ProductListRequest $request, string $categorySlug): JsonResponse
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $filters = $request->validated();

        $products = $this->productRepository->getFilteredProducts($filters, $category);
        $availableFilters = $this->optionsService->getAvailableFilters($category);

        return response()->json([
            'products' => $products,
            'filters' => $availableFilters,
            'category' => $category
        ]);
    }

    public function search(ProductListRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $products = $this->productRepository->getFilteredProducts($filters);

        return response()->json([
            'products' => $products,
            'search_term' => $filters['search'] ?? null
        ]);
    }
}
