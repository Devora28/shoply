<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
        return [
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
            'name' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'short_description' => $this->faker->text(),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 10,15),
            'discount' => $this->faker->numberBetween(0,100),
            'sku' => $this->faker->unique()->ean8(),
            'stock' => $this->faker->numberBetween(0, 100),
            'rating' => $this->faker->randomFloat(1,0,5),
            'sold_count' => $this->faker->numberBetween(0, 100),
            'view_count' => $this->faker->numberBetween(0, 100),
            'is_featured' => $this->faker->boolean(),
            'is_active' => true,
            'weight' => $this->faker->randomFloat(2,0.1,100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
