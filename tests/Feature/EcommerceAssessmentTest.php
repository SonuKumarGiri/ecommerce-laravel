<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;

class EcommerceAssessmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup base data
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->customer = User::factory()->create(['is_admin' => false]);
        $this->category = Category::factory()->create();
    }

    public function test_product_creation()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Test Product',
            'category_id' => $this->category->id,
            'price' => 1500,
            'stock' => 10,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_product_validation()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => '', // Invalid
            'price' => -10, // Invalid
        ]);

        $response->assertSessionHasErrors(['name', 'price', 'category_id']);
    }

    public function test_product_listing()
    {
        Product::factory(5)->create(['status' => 'active']);
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
    }

    public function test_add_to_cart()
    {
        $product = Product::factory()->create(['stock' => 10, 'status' => 'active']);
        $response = $this->actingAs($this->customer)->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 2]);
    }

    public function test_cart_quantity_update()
    {
        $product = Product::factory()->create(['stock' => 10, 'status' => 'active']);
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $item = $cart->items()->create(['product_id' => $product->id, 'quantity' => 1, 'price' => $product->price]);

        $response = $this->actingAs($this->customer)->put(route('cart.update', $item->id), [
            'quantity' => 5,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cart_items', ['id' => $item->id, 'quantity' => 5]);
    }

    public function test_insufficient_stock_prevents_order()
    {
        $product = Product::factory()->create(['stock' => 2, 'status' => 'active']);
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $item = $cart->items()->create(['product_id' => $product->id, 'quantity' => 5, 'price' => $product->price]);

        $response = $this->actingAs($this->customer)->post(route('checkout.store'), [
            'name' => 'John',
            'email' => 'john@test.com',
            'mobile' => '1234567890',
            'address' => '123 Street',
            'city' => 'City',
            'state' => 'State',
            'pincode' => '12345',
            'payment_method' => 'COD',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_order_creation()
    {
        $product = Product::factory()->create(['stock' => 10, 'price' => 100, 'status' => 'active']);
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $cart->items()->create(['product_id' => $product->id, 'quantity' => 2, 'price' => 100]);

        $response = $this->actingAs($this->customer)->post(route('checkout.store'), [
            'name' => 'John',
            'email' => 'john@test.com',
            'mobile' => '1234567890',
            'address' => '123 Street',
            'city' => 'City',
            'state' => 'State',
            'pincode' => '12345',
            'payment_method' => 'COD',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['user_id' => $this->customer->id, 'total_amount' => 200]);
        // Stock should be reduced
        $this->assertEquals(8, $product->fresh()->stock);
    }

    public function test_order_cancellation()
    {
        $order = Order::factory()->create(['user_id' => $this->customer->id, 'status' => 'placed']);
        $product = Product::factory()->create(['stock' => 5]);
        $order->items()->create(['product_id' => $product->id, 'quantity' => 2, 'price' => 100, 'total' => 200]);

        $response = $this->actingAs($this->customer)->post(route('orders.cancel', $order->id));

        $response->assertRedirect();
        $this->assertEquals('cancelled', strtolower($order->fresh()->status));
        // Stock should be restored
        $this->assertEquals(7, $product->fresh()->stock);
    }

    public function test_payment_simulation()
    {
        // Using Sanctum authentication for API
        $token = $this->customer->createToken('test')->plainTextToken;
        $order = Order::factory()->create(['user_id' => $this->customer->id, 'status' => 'pending', 'total_amount' => 500]);

        // Note: Payment API has a 10% chance of failure intentionally for simulation. 
        // We will just test that it returns a JSON response and modifies the DB.
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
             ->postJson('/api/payment/process', [
                 'order_id' => $order->id,
                 'amount' => 500,
             ]);

        $this->assertTrue(in_array($response->status(), [200, 400])); 
        $this->assertNotEquals('pending', strtolower($order->fresh()->status));
    }

    public function test_admin_updates_order_status()
    {
        $order = Order::factory()->create(['status' => 'placed']);
        
        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order->id), [
            'status' => 'SHIPPED',
            'payment_status' => 'SUCCESS',
        ]);

        $response->assertRedirect();
        $this->assertEquals('SHIPPED', $order->fresh()->status);
    }

    public function test_remove_cart_item()
    {
        $product = Product::factory()->create(['stock' => 10, 'status' => 'active']);
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $item = $cart->items()->create(['product_id' => $product->id, 'quantity' => 1, 'price' => 100]);

        $response = $this->actingAs($this->customer)->delete(route('cart.destroy', $item->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_guest_cart_migrates_to_user_upon_login()
    {
        $product = Product::factory()->create(['stock' => 10, 'status' => 'active']);

        // Guest adds product to cart
        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $guestCart = Cart::whereNull('user_id')->first();
        $this->assertNotNull($guestCart);
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $guestCart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // Login as customer, simulating browser sending the guest_cart_session cookie
        $loginResponse = $this->withCookies([
            'guest_cart_session' => $guestCart->session_id,
        ])->post(route('login'), [
            'email' => $this->customer->email,
            'password' => 'password',
        ]);

        $loginResponse->assertRedirect();

        // Customer's cart should now contain the migrated items
        $userCart = Cart::where('user_id', $this->customer->id)->first();
        $this->assertNotNull($userCart);
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $userCart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // Guest cart should be cleaned up
        $this->assertDatabaseMissing('carts', ['id' => $guestCart->id]);
    }
}
