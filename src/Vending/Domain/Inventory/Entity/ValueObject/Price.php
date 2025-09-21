<?php

namespace App\Vending\Domain\Inventory\Entity\ValueObject;

use App\Vending\Domain\Inventory\Exception\InvalidPriceValueException;

class Price
{
    public function __construct(private float $price)
    {
        $this->validate($price);
    }

    public function getValue(): float
    {
        return round($this->price, 2);
    }

    private function validate(float $price): void
    {
        if ($price <= 0) {
            throw new InvalidPriceValueException();
        }
    }
}
