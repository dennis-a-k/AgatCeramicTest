<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductExportController;
use App\Http\Controllers\Admin\BulkProductController;
use Illuminate\Support\Facades\Route;

Route::get('export/products', [ProductExportController::class, 'export']);
Route::apiResource('products', ProductController::class);
Route::post('/products/bulk-upload', [BulkProductController::class, 'bulkUpload']);
