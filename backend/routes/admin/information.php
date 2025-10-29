<?php

use App\Http\Controllers\Admin\InformationController;
use Illuminate\Support\Facades\Route;

Route::get('/information', [InformationController::class, 'index']);
Route::put('/information', [InformationController::class, 'update']);