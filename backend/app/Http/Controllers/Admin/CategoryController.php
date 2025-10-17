<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request): JsonResponse
    {
        $query = Category::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->get('per_page', 5);
        if ($perPage === 'all') {
            $categories = $query->orderBy('order')->get();
            return response()->json(['data' => $categories]);
        } else {
            $categories = $query->orderBy('order')->paginate($perPage);
            return response()->json($categories);
        }
    }

    public function show($id): JsonResponse
    {
        $category = $this->categoryService->getCategoryById($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category->load('attributes'));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'parent_id' => 'nullable|exists:categories,id',
            'is_plumbing' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // If is_plumbing is true, set parent_id to the plumbing category
        if ($request->boolean('is_plumbing') && !$request->parent_id) {
            $plumbingCategory = Category::where('name', 'Сантехника')->first();
            if ($plumbingCategory) {
                $data['parent_id'] = $plumbingCategory->id;
            }
        }

        // Remove is_plumbing from data as it's not a database field
        unset($data['is_plumbing']);

        // If is_plumbing is true, set parent_id to the plumbing category
        if ($request->is_plumbing && !$request->parent_id) {
            $plumbingCategory = Category::where('name', 'Сантехника')->first();
            if ($plumbingCategory) {
                $data['parent_id'] = $plumbingCategory->id;
            }
        }

        $category = $this->categoryService->createCategory($data);
        return response()->json($category, 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'parent_id' => 'nullable|exists:categories,id',
            'is_plumbing' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $updated = $this->categoryService->updateCategory($id, $data);

        if (!$updated) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category = $this->categoryService->getCategoryById($id);
        return response()->json($category);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->categoryService->deleteCategory($id);

        if (!$deleted) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
