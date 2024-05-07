<?php

namespace Database\Factories;

use App\Models\Cut;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $id_cut = Cut::pluck('id')->toArray();
        return [
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'weight' => fake()->randomFloat(2, 1, 100),
            'image' => 'path/to/image.jpg',
            'image2' => 'path/to/image2.jpg',
            'id_cut' => fake()->randomElement($id_cut),
        ];
    }
}
