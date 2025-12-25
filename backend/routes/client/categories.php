<?php

use App\Http\Controllers\Client\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/client/categories/slugs', [CategoryController::class, 'slugs']);
Route::get('/client/categories/{slug}/children', [CategoryController::class, 'children']);
