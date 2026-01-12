<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Product;
use App\Models\User;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        // Generar múltiples ventas
        for ($i = 0; $i < 10; $i++) {
            // Seleccionar productos aleatorios
            $products_number = rand(1, 5);
            $products = Product::inRandomOrder()->take($products_number)->get();

            // Calcular total y cantidades
            $total = 0;
            $quantities = [];

            foreach ($products as $product) {
                $quantity = rand(1, 5);
                $quantities[$product->id] = $quantity;
                $total += $product->price * $quantity;
            }

            // Seleccionar usuario aleatorio
            $user = User::inRandomOrder()->first();

            // Crear la venta con usuario, total y fecha aleatoria
            $sale = Sale::factory()->create([
                'user_id' => $user->id,
                'total_price' => $total,
                'sale_date' => now()->subDays(rand(0, 30)),
            ]);

            // Asociar productos con cantidades
            foreach ($products as $product) {
                $sale->products()->attach($product->id, [
                    'quantity' => $quantities[$product->id],
                ]);
            }
        }
    }
}
