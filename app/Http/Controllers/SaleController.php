<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Sale;
use App\Models\Sale_Product;

class SaleController extends Controller
{
    // Listar todas las ventas
    public function index()
    {
        return Sale::with('products')->get();
    }

    // Crear una venta
    public function store(SaleRequest $request)
    {
        $sale = Sale::create($request->validated());
        return $sale->load('products');
    }

    // Mostrar una venta
    public function show(Sale $sale)
    {
        return $sale->load('products');
    }

    // Actualizar una venta
    public function update(SaleRequest $request, Sale $sale)
    {
        $sale->update($request->validated());
        return $sale->load('products');
    }

    // Eliminar una venta
    public function destroy(Sale $sale)
    {
        $sale->delete();
        return response()->json(['message' => 'Venta eliminada']);
    }
}