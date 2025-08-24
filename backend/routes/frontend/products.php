<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('products', [ProductController::class, 'index']);
Route::get('products/category/{categorySlug}', [ProductController::class, 'byCategory']);
Route::get('products/search', [ProductController::class, 'search']);

// Дополнительные routes для категорий, брендов и т.д.
Route::get('categories', function () {
    return \App\Models\Category::where('is_active', true)->get();
});

Route::get('brands', function () {
    return \App\Models\Brand::where('is_active', true)->get();
});
Route::get('colors', function () {
    return \App\Models\Color::where('is_active', true)->get();
});
