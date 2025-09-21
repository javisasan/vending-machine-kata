<?php

namespace App\SharedKernel\Domain\ValueObject;

use App\SharedKernel\Domain\Exception\InvalidQuantityValueException;

class Quantity
{
    public function __construct(private int $quantity)
    {
        $this->validate($quantity);
    }

    public function getValue(): int
    {
        return $this->quantity;
    }

    private function validate(int $quantity): void
    {
        if ($quantity < 0) {
            throw new InvalidQuantityValueException();
        }
    }
}
