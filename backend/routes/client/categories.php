<?php

use App\Http\Controllers\Client\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/categories/slugs', [CategoryController::class, 'slugs']);
Route::get('/categories/{slug}/children', [CategoryController::class, 'children']);
