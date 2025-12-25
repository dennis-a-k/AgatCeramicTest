<?php

use App\Http\Controllers\Client\BrandController;;
use Illuminate\Support\Facades\Route;

Route::get('/client/brands/categories', [BrandController::class, 'getByCategories']);
Route::apiResource('/client/brands', BrandController::class)->only(['index', 'show']);
