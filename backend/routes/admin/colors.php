<?php

use App\Http\Controllers\Admin\ColorController;
use Illuminate\Support\Facades\Route;

Route::apiResource('colors', ColorController::class);
