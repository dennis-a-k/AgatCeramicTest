<?php

use App\Http\Controllers\Client\ProductController;
use Illuminate\Support\Facades\Route;

// Получение товара по slug
Route::get('/client/products/slug/{slug}', [ProductController::class, 'showBySlug']);
// Получение списка товаров по выбранному бренду
Route::get('/client/brand/{slug}/products', [ProductController::class, 'getByBrand']);
// Получение списка товаров по выбранной категории
Route::get('/client/category/{slug}/products', [ProductController::class, 'getByCategory']);
// Получение списка товаров на распродаже
Route::get('/client/sale/products', [ProductController::class, 'getBySale']);
