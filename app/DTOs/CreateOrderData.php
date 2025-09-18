<?php

namespace App\DTOs;

class CreateOrderData
{

    public function __construct(
        public int $userId,
        public array $items,
    ) {
    }


    public static function fromArray(array $payload): self
    {
        return new self(
            userId: (int) $payload['user_id'],
            items: array_map(function ($item) {
                return [
                    'product_id' => (int) $item['product_id'],
                    'quantity' => (int) $item['quantity'],
                ];
            }, $payload['items'])
        );
    }
}


