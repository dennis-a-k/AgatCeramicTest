<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InformationController extends Controller
{
    public function index(): JsonResponse
    {
        $information = Information::first();

        if (!$information) {
            $information = Information::create([]);
        }

        return response()->json($information);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telegram' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'adress' => 'nullable|string|max:255',
            'inn' => 'nullable|string|max:255',
            'ogrn' => 'nullable|string|max:255',
            'okato' => 'nullable|string|max:255',
            'okpo' => 'nullable|string|max:255',
            'bank' => 'nullable|string|max:255',
            'bik' => 'nullable|string|max:255',
            'ks' => 'nullable|string|max:255',
            'rs' => 'nullable|string|max:255',
        ]);

        $information = Information::first();

        if (!$information) {
            $information = Information::create($validated);
        } else {
            $information->update($validated);
        }

        return response()->json($information);
    }
}
