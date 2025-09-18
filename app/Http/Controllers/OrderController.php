<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Services\Contracts\OrderServiceInterface;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct(private readonly OrderServiceInterface $orderService)
    {
    }

    public function index()
    {
        $orders = $this->orderService->listWithRelations();
        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'products']);
        return new OrderResource($order);
    }

    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();
        $order = $this->orderService->createOrder($validated);
        return (new OrderResource($order))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateOrderStatusRequest $request, Order $order)
    {
        $validated = $request->validated();
        $order = $this->orderService->updateStatus($order, $validated['status']);
        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $this->orderService->delete($order);
        return response()->json(['message' => 'Order deleted']);
    }
}



