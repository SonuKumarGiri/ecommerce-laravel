<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $status = $this->faker->randomElement(['placed', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled']);
        
        $paymentStatus = 'pending';
        if (in_array($status, ['processing', 'shipped', 'delivered'])) {
            $paymentStatus = 'paid';
        } elseif ($status === 'cancelled') {
            $paymentStatus = $this->faker->randomElement(['refunded', 'failed']);
        }

        return [
            'user_id' => User::where('is_admin', false)->inRandomOrder()->first()->id ?? User::factory(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => 0, // Will be updated by seeder after adding items
            'status' => $status,
            'payment_status' => $paymentStatus,
            'shipping_name' => $this->faker->name(),
            'shipping_mobile' => $this->faker->numerify('##########'),
            'shipping_address' => $this->faker->streetAddress(),
            'shipping_city' => $this->faker->city(),
            'shipping_pincode' => $this->faker->postcode(),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
