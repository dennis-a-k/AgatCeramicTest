<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Color::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('hex', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->get('per_page', 5);
        if ($perPage == 'all') {
            $colors = $query->orderBy('name', 'asc')->get();
            return response()->json(['data' => $colors]);
        } else {
            $colors = $query->orderBy('name', 'asc')->paginate($perPage);
            return response()->json($colors);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name',
            'hex' => 'required|string|size:7|unique:colors,hex|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $color = Color::create($data);

        return response()->json(['color' => $color], 201);
    }

    public function show($id): JsonResponse
    {
        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }

        return response()->json(['color' => $color]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,' . $id,
            'hex' => 'required|string|size:7|unique:colors,hex,' . $id . '|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $color->update($data);

        return response()->json(['color' => $color]);
    }

    public function destroy($id): JsonResponse
    {
        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }

        $color->delete();

        return response()->json(['message' => 'Color deleted successfully']);
    }
}
