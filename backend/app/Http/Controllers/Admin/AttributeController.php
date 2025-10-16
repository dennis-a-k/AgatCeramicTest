<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Attribute::with('categories');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->get('per_page', 5);
        $attributes = $query->orderBy('name', 'asc')->paginate($perPage);
        return response()->json($attributes);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
            'type' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $attribute = Attribute::create($data);

        return response()->json(['attribute' => $attribute], 201);
    }

    public function show($id): JsonResponse
    {
        $attribute = Attribute::with('categories')->find($id);

        if (!$attribute) {
            return response()->json(['message' => 'Attribute not found'], 404);
        }

        return response()->json(['attribute' => $attribute]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json(['message' => 'Attribute not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name,' . $id,
            'type' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $attribute->update($data);

        return response()->json(['attribute' => $attribute]);
    }

    public function destroy($id): JsonResponse
    {
        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json(['message' => 'Attribute not found'], 404);
        }

        $attribute->delete();

        return response()->json(['message' => 'Attribute deleted successfully']);
    }
}
