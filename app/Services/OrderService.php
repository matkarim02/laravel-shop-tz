<?php

namespace App\Services;

use App\Services\Contracts\OrderServiceInterface;
use App\DTOs\CreateOrderData;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class OrderService implements OrderServiceInterface
{
    public function listWithRelations()
    {
        return Order::with(['user', 'products'])
            ->orderByDesc('id')
            ->get();
    }

    public function createOrder(CreateOrderData $data): Order
    {
        return DB::transaction(function () use ($data) {
            $key = request()->input('idempotency_key');
            if ($key) {
                $existing = Order::with(['user', 'products'])
                    ->where('user_id', $data->userId)
                    ->where('idempotency_key', $key)
                    ->first();
                if ($existing) {
                    return $existing;
                }
            }

            $order = new Order();
            $order->user_id = $data->userId;
            $order->status = 'new';
            $order->total_price = 0;
            $order->idempotency_key = $key;
            $order->save();

            $total = 0;
            foreach ($data->items as $item) {
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



