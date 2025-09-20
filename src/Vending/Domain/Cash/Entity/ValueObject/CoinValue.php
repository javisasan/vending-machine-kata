<?php

namespace App\Vending\Domain\Cash\Entity\ValueObject;

use App\Vending\Domain\Cash\Exception\InvalidCoinAmountException;

class CoinValue
{
    public const FIVE_CENTS = 0.05;
    public const TEN_CENTS = 0.1;
    public const TWENTY_FIVE_CENTS = 0.25;
    public const ONE_HUNDRED_CENTS = 1;

    private const VALID_VALUES = [
        self::FIVE_CENTS,
        self::TEN_CENTS,
        self::TWENTY_FIVE_CENTS,
        self::ONE_HUNDRED_CENTS,
    ];

    public function __construct(private float $value)
    {
        $this->validate($value);
    }

    public function getValue(): float
    {
        return $this->value;
    }

    private function validate(float $value): void
    {
        if (!in_array($value, self::VALID_VALUES)) {
            throw new InvalidCoinAmountException();
        }
    }
}
