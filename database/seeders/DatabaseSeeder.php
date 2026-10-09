<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admins
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // 2. Create Customers
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);
        User::factory(49)->create([
            'is_admin' => false,
        ]); // Total ~50 customers

        // 3. Create Categories
        Category::factory(50)->create();

        // 4. Create Products
        Product::factory(50)->create();

        // 5. Create Orders (and OrderItems)
        $orders = Order::factory(50)->create();

        foreach ($orders as $order) {
            // Give each order 1 to 3 items
            $numItems = rand(1, 3);
            $items = OrderItem::factory($numItems)->create([
                'order_id' => $order->id,
            ]);

            // Update order total
            $order->update([
                'total_amount' => $items->sum('total'),
            ]);
        }

        // 6. Seed System Settings
        $this->call(SettingsSeeder::class);
    }
}
