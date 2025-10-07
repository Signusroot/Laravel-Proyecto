<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   // Listar todos los productos
    public function index()
    {
        return Product::all();
    }

    // Crear un producto
    public function store(StoreProductRequest $request)
    {
        return Product::create($request->Validated());
    }

    // Mostrar un producto
    public function show(Product $product)
    {
        return $product;
    }

    // Actualizar un producto
    public function update(StoreProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return $product;
    }

    // Eliminar un producto
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Producto eliminado']);
    }
}
