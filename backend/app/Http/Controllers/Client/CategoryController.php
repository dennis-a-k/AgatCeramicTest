<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Category::with(['children', 'filterableAttributes'])
            ->where('is_active', true)
            ->findOrFail($id);

        return response()->json($category);
    }
}
