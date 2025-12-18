<?php

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/products', ProductController::class);
Route::post('/products/bulk-upload', [ProductController::class, 'bulkUpload']);
