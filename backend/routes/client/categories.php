<?php

use App\Http\Controllers\Client\CategoryController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::get('/categories/slugs', [CategoryController::class, 'slugs']);
