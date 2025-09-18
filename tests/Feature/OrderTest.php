<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_index_returns_orders_with_relations(): void
    {
        $user = User::factory()->create();
        $products = Product::factory()->count(3)->create();

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'new',
            'total_price' => 0,
        ]);

        $total = 0;
        foreach ($products as $product) {
            $order->products()->attach($product->id, ['quantity' => 1]);
            $total += $product->price;
        }
        $order->update(['total_price' => $total]);

        $response = $this->get('/orders');
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $order->id]);
        $response->assertJsonCount(3, '0.products');
    }

    public function test_order_creation(): void
    {
        $user = User::factory()->create();
        $products = Product::factory()->count(2)->create();
        $payload = [
            'user_id' => $user->id,
            'items' => [
                ['product_id' => $products[0]->id, 'quantity' => 2],
                ['product_id' => $products[1]->id, 'quantity' => 1],
            ],
        ];

        $first = $this->postJson('/orders', $payload);
        $first->assertStatus(201);
    }
}





