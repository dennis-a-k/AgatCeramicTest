<?php

use App\Http\Controllers\Client\ColorController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/colors', ColorController::class)->only(['index', 'show']);
