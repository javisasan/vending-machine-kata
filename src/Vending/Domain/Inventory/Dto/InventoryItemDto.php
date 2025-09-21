<?php

namespace App\Vending\Domain\Inventory\Dto;

class InventoryItemDto
{
    public function __construct(
        private string $selector,
        private float $price,
        private int $quantity
    ) {
    }

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
