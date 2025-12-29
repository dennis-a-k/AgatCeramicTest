<?php

use App\Http\Controllers\Client\BrandController;;
use Illuminate\Support\Facades\Route;

Route::get('/client/brands/categories', [BrandController::class, 'getByCategories']);
Route::get('/client/brands/slugs', [BrandController::class, 'slugs']);
Route::apiResource('/client/brands', BrandController::class)->only(['index', 'show']);
