<?php

use App\Http\Controllers\Client\BrandController;;
use Illuminate\Support\Facades\Route;

Route::get('/brands/categories', [BrandController::class, 'getByCategories']);
Route::apiResource('/brands', BrandController::class)->only(['index', 'show']);
