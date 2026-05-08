<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'getToken']);

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    // Product Routes
    Route::post('/product', [ProductController::class, 'store']);
    Route::get('/product', [ProductController::class, 'index']);
    Route::put('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);

    // Category Routes
    Route::apiResource('category', CategoryController::class);
});

// Public Product View (based on task description)
Route::get('/product/{id}', [ProductController::class, 'show']);
