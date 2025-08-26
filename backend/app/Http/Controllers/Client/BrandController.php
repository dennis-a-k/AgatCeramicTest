<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\BrandService;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index(): JsonResponse
    {
        $brands = $this->brandService->getAllBrands();
        return response()->json($brands);
    }

    public function show($id): JsonResponse
    {
        $brand = $this->brandService->getBrandById($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        return response()->json($brand);
    }
}
