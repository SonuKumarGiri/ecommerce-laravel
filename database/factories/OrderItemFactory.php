<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 5);
        
        return [
            'order_id' => Order::factory(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price,
            'total' => $product->price * $quantity,
        ];
    }
}
