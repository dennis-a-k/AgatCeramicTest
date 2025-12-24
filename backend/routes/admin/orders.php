<?php

use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/orders/statistics', [OrderController::class, 'statistics']);
Route::get('/orders/export', [OrderController::class, 'export']);
Route::apiResource('/orders', OrderController::class)->only(['index', 'show', 'update']);
