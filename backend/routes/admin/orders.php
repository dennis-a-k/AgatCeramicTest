<?php

use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/orders', OrderController::class)->only(['index', 'show', 'update']);
