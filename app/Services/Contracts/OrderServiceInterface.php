<?php

namespace App\Services\Contracts;

use App\Models\Order;

interface OrderServiceInterface
{
    public function listWithRelations();

    public function createOrder(array $data): Order;

    public function updateStatus(Order $order, string $status): Order;

    public function delete(Order $order): void;
}




