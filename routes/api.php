<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']); //Crear nuevos usuarios
    Route::put('/{user}', [UserController::class, 'update']); //Actualizar usuario
    Route::delete('/{user}', [UserController::class, 'destroy']); //Eliminar usuario
    Route::get('/', [UserController::class, 'index']); //Listar los usuarios
});

Route::apiResource('users', UserController::class); 


// Rutas que requieren autenticación (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());

    // Protege las operaciones de productos y ventas
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('sales', SaleController::class); 
    

    
});
Route::get('users', [UserController::class, 'index']); //Listar los usuarios
