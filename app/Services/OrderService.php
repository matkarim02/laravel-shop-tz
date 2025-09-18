<?php

namespace App\Services;

use App\Services\Contracts\OrderServiceInterface;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    public function listWithRelations()
    {
        return Order::with(['user', 'products'])
            ->orderByDesc('id')
            ->get();
    }

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = new Order();
            $order->user_id = $data['user_id'];
            $order->status = 'new';
            $order->total_price = 0;
            $order->save();

            $total = 0;
            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
                $quantity = $item['quantity'];
                $order->products()->attach($product->id, ['quantity' => $quantity]);
                $total += $product->price * $quantity;
            }

            $order->total_price = $total;
            $order->save();

            return $order->load(['user', 'products']);
        });
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $order->status = $status;
        $order->save();
        return $order->load(['user', 'products']);
    }

    public function delete(Order $order): void
    {
        $order->delete();
    }
}



