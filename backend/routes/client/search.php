<?php

use App\Http\Controllers\Client\SearchController;
use Illuminate\Support\Facades\Route;

// Основной поиск товаров
Route::post('/search', [SearchController::class, 'search']);

// Быстрый поиск для автодополнения
Route::get('/search/quick', [SearchController::class, 'quickSearch']);
