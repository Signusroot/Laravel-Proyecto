<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

/*
//Crear la Venta
Route::get('/sales/created', [SaleController::class, 'show'])->name('sales.show');
Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
*/