<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function slugs(): JsonResponse
    {
        $slugs = $this->categoryService->getAllCategorySlugs();
        return response()->json($slugs);
    }

    public function children($slug): JsonResponse
    {
        $children = $this->categoryService->getChildrenBySlug($slug);
        return response()->json($children);
    }
}
