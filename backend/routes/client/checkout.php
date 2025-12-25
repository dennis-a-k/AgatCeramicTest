<?php

use App\Http\Controllers\Client\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::post('/client/checkout', [CheckoutController::class, 'store']);
