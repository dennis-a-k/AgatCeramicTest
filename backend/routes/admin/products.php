<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductExportController;
use App\Http\Controllers\Admin\BulkProductController;
use Illuminate\Support\Facades\Route;

Route::get('export/edit-template/{template_type}', [ProductExportController::class, 'editTemplate']);
Route::get('export/edit-template-category/{category}', [ProductExportController::class, 'editTemplateCategory']);
Route::get('export/products', [ProductExportController::class, 'export']);
Route::get('export/template/{category}', [ProductExportController::class, 'template']);
Route::apiResource('products', ProductController::class);
Route::post('/products/bulk-upload', [BulkProductController::class, 'bulkUpload']);
Route::post('/products/bulk-edit', [BulkProductController::class, 'bulkEdit']);
Route::post('/products/bulk-photo-upload', [BulkProductController::class, 'bulkPhotoUpload']);
