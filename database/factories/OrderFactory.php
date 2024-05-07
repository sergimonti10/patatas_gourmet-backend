<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $id_user = User::pluck('id')->toArray();
        return [
            'date_order' => fake()->date(),
            'date_deliver' => fake()->dateTimeBetween('+1 day', '+1 week'),
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'completed', 'canceled']),
            'total_price' => fake()->randomFloat(2, 10, 1000),
            'total_products' => fake()->numberBetween(1, 10),
            'id_user' => fake()->randomElement($id_user),
        ];
    }
}
