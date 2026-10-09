<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 5000),
            'stock' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'inactive']),
            'image' => null,
        ];
    }
}
