<?php

use App\Http\Controllers\Admin\AttributeController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/attributes', AttributeController::class);
