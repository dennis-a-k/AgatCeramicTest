<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/users', UserController::class)->except(['store', 'show', 'update']);
