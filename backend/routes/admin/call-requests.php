<?php

use App\Http\Controllers\Admin\CallRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/call-requests/statistics', [CallRequestController::class, 'statistics']);
Route::apiResource('/admin/call-requests', CallRequestController::class)->only(['index', 'show', 'update']);
