<?php

use App\Http\Controllers\Admin\CallRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/call-requests/statistics', [CallRequestController::class, 'statistics']);
Route::apiResource('/call-requests', CallRequestController::class)->only(['index', 'show', 'update']);
