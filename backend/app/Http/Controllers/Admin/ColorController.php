<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\JsonResponse;

class ColorController extends Controller
{
    public function index(): JsonResponse
    {
        $colors = Color::all();
        return response()->json(['colors' => $colors]);
    }

    public function show($id): JsonResponse
    {
        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }

        return response()->json(['color' => $color]);
    }
}
