<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);

// Rutas que requieren autenticación (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());

    // Protege las operaciones de productos y ventas
    Route::apiResource('products', ProductController::class);
    Route::apiResource('sales', SaleController::class);

    Route::get('dashboard', [DashboardController::class, 'index']);
});