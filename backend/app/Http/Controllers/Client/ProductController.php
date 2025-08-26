<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
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

    public function search(Request $request, $query): JsonResponse
    {
        $request->merge(['search' => $query]);
        $result = $this->productService->getProductsWithFilters($request);
        return response()->json($result);
    }
}
