<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($brands);
    }

    public function show($id)
    {
        $brand = Brand::where('is_active', true)
            ->findOrFail($id);

        return response()->json($brand);
    }
}
