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

    /**
     * Получает список брендов, связанных с заданными категориями.
     * Метод извлекает бренды для следующих категорий: керамогранит, плитка, мозаика, клинкер, ступени.
     * @return JsonResponse JSON-ответ с массивом брендов
     */
    public function getByCategories(): JsonResponse
    {
        $categorySlugs = ['keramogranit', 'plitka', 'mozaika', 'klinker', 'stupeni'];
        $brands = $this->brandService->getBrandsByCategories($categorySlugs);
        return response()->json($brands);
    }
}
