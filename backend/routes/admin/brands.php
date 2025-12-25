<?php

use App\Http\Controllers\Admin\BrandController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/admin/brands', BrandController::class)->names('admin.brands');
