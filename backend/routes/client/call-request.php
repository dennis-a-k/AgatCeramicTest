<?php

use App\Http\Controllers\Client\CallRequestController;
use Illuminate\Support\Facades\Route;

Route::post('/call-request', [CallRequestController::class, 'store']);
