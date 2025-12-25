<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductExportController;
use App\Http\Controllers\Admin\BulkProductController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/export/edit-template/{template_type}', [ProductExportController::class, 'editTemplate']);
Route::get('/admin/export/edit-template-category/{category}', [ProductExportController::class, 'editTemplateCategory']);
Route::get('/admin/export/products', [ProductExportController::class, 'export']);
Route::get('/admin/export/template/{category}', [ProductExportController::class, 'template']);
Route::apiResource('/admin/products', ProductController::class);
Route::post('/admin/products/bulk-upload', [BulkProductController::class, 'bulkUpload']);
Route::post('/admin/products/bulk-edit', [BulkProductController::class, 'bulkEdit']);
Route::post('/admin/products/bulk-photo-upload', [BulkProductController::class, 'bulkPhotoUpload']);
