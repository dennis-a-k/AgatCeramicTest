<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ColorService;
use Illuminate\Http\JsonResponse;

class ColorController extends Controller
{
    protected $colorService;

    public function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
    }

    public function index(): JsonResponse
    {
        $colors = $this->colorService->getAllColors();
        return response()->json(['data' => $colors]);
    }

    public function show($id): JsonResponse
    {
        $color = $this->colorService->getColorById($id);

        if (!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }

        return response()->json($color);
    }
}
