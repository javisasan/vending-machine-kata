<?php

namespace App\VendingMachine\Application\Query;

class StatusQueryHandlerResponse
{
    private array $products = [
        [
            'selector' => 'water',
            'price' => 0.65,
            'count' => 5,
        ],
    ];

    public function getProducts(): array
    {
        return $this->products;
    }
}
