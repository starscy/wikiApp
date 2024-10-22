<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\WikiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;

Route::prefix('wiki')->group(function () {
    Route::any('/', [WikiController::class, 'index']);
    Route::any('/parse', [WikiController::class, 'parse']);
});

Route::prefix('articles')->group(function () {
    // Сохранение данных в БД
    Route::post('/save', [ArticleController::class, 'store']);

    // Получение всех статей
    Route::get('/', [ArticleController::class, 'index']);

    // Поиск статей
    Route::get('/search', [ArticleController::class, 'search']);

    // Поиск статьи по ID
    Route::get('/show', [ArticleController::class, 'show']);
});
