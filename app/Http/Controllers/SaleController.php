<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\User;
use App\Models\Sale;
use App\Models\Product;


class SaleController extends Controller
{
    // Listar todas las ventas
    public function index()
    {
        return Sale::with('products')->get();
    }

    // Crear una venta
    public function store(SaleRequest $request, ){
        
        $validated = $request->validated();

        //Buscar por la Cedula
        $user = User::where('cedula',$validated['cedula'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado con esa cédula en la Base de Datos'
            ], 404);
            
        }

        //Inicializar Variable reiniciada, Calcular total y preparar productos
        $total=0;
        $quantity = [];

        foreach($validated['products'] as $item){

            $product = Product::find($item['product_id']);
            $quantity = $item['quantity'];
            $productData[$product->id] = ['quantity' => $quantity]; 
            $total += $product->price * $quantity;

        }
            // Seleccionar usuario aleatorio
            $user = User::inRandomOrder()->first();

            // Crear la venta con usuario, total y fecha aleatoria
            $sale = Sale::factory()->create([
                'user_id' => $user->id,
                'total_price' => $total,
                'sale_date' => now(),
            ]);
        

        $sale->products()->attach($productData);

        return response()->json([
            'message' => 'Venta registrada correctamente',
            'sale' => [
                'id' => $sale->id,
                'total_price' => $sale->total_price,
                'sale_date' => $sale->sale_date,
                'products' => $sale->products->map(function ($product) {
                    return [
                        'name' => $product->name,
                        'quantity' => $product->pivot->quantity,
                        'price' => $product->price,
                    ];
                }),
            ]
        ], 201);

        
        

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