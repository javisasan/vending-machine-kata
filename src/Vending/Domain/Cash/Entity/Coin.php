<?php

namespace App\Vending\Domain\Cash\Entity;

use App\Vending\Domain\Cash\Entity\ValueObject\CoinValue;

class Coin
{
    private function __construct(private CoinValue $value)
    {
    }

    public static function create(float $value): self
    {
        $coinValue = new CoinValue($value);

        return new self($coinValue);
    }

    public function getValue(): float
    {
        return round($this->value->getValue(), 2);
    }
}
