<?php

namespace App\Vending\Application\Machine\Command;

class CreditCommand
{
    public function __construct(private float $value)
    {
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
