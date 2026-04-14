<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;

// ---- Public Routes ----
Route::post('/login', [AuthController::class, 'login']);

// ---- Protected Routes (butuh JWT token) ----
Route::middleware('auth:api')->group(function () {
    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::get('/me',       [AuthController::class, 'me']);

    Route::apiResource('items', ItemController::class);
});
