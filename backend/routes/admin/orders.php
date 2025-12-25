<?php

use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/orders/statistics', [OrderController::class, 'statistics']);
Route::get('/admin/orders/export', [OrderController::class, 'export']);
Route::apiResource('/admin/orders', OrderController::class)->only(['index', 'show', 'update']);
