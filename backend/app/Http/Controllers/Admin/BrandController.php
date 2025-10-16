<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Brand::query();

        // Поиск по названию
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Пагинация
        $perPage = $request->get('per_page', 5);
        $brands = $query->orderBy('name', 'asc')->paginate($perPage);

        return response()->json($brands);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|dimensions:max_width=300,max_height=300|max:1024',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->storeAs('brands', $data['slug'] . '.' . $extension, 'public');
            $data['image'] = $imagePath;
        }

        $brand = Brand::create($data);

        return response()->json(['brand' => $brand], 201);
    }

    public function show($id): JsonResponse
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        return response()->json(['brand' => $brand]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|dimensions:max_width=300,max_height=300|max:1024',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            // Удалить старое изображение, если есть
            if ($brand->image && Storage::disk('public')->exists($brand->image)) {
                Storage::disk('public')->delete($brand->image);
            }
            $extension = $request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->storeAs('brands', $data['slug'] . '.' . $extension, 'public');
            $data['image'] = $imagePath;
        }

        $brand->update($data);

        return response()->json(['brand' => $brand]);
    }

    public function destroy($id): JsonResponse
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        $brand->delete();

        return response()->json(['message' => 'Brand deleted successfully']);
    }
}
