<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class SaleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'total_price' => $this->faker->numberBetween(100, 1000),
            'sale_date' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

}
