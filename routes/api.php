<?php

use App\Http\Controllers\Api\FavoriteStockController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\StockController;
use Illuminate\Routing\Middleware\ThrottleRequests;

// Authentication routes
Route::prefix('auth')->group(function () {
    // Apply rate limiting: 5 requests per minute
    Route::post('register', [AuthController::class, 'register'])->middleware(ThrottleRequests::class.':5,1');
    Route::post('login', [AuthController::class, 'login'])->middleware(ThrottleRequests::class.':5,1');


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'me']);
    });
});

// Protected Stock routes (outside /auth)
Route::middleware('auth:sanctum')->prefix('stocks')->group(function () {
    // Favorite operations
    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoriteStockController::class, 'index']);
        Route::post('/{symbol}', [FavoriteStockController::class, 'store']);
        Route::delete('/{symbol}', [FavoriteStockController::class, 'destroy']);
    });

    // Stock data
    Route::get('/', [StockController::class, 'index']);
    Route::get('/{symbol}', [StockController::class, 'show']);
});
