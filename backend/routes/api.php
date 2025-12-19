<?php

use App\Http\Controllers\Admin\ProductExportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('export/edit-template/{type}', [ProductExportController::class, 'editTemplate']);

require __DIR__ . '/client/api.php';
require __DIR__ . '/admin/api.php';
require __DIR__ . '/search.php';
