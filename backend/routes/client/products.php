<?php

use App\Http\Controllers\Client\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class)->only(['index', 'show']);
// Получение списка товаров по выбранному бренду
Route::get('/brand/{slug}/products', [ProductController::class, 'getByBrand']);
// Получение списка товаров по выбранной категории
Route::get('/category/{slug}/products', [ProductController::class, 'getByCategory']);
// Получение списка товаров на распродаже
Route::get('/sale/products', [ProductController::class, 'getBySale']);
