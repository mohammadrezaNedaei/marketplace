<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'seller_id' => User::factory()->seller(),
            'category_id' => CategoryFactory::new()->create()->id,
            'picture_url' => fake()->imageUrl(640, 480, 'product'),
            'title' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 1000, 500000),
            'discount_price' => null,
            'file_url' => null,
            'status' => 'active',
        ];
    }
}
