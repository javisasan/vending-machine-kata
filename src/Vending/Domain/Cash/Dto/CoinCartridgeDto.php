<?php

namespace App\Vending\Domain\Cash\Dto;

class CoinCartridgeDto
{
    public function __construct(private float $value, private int $quantity)
    {
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
