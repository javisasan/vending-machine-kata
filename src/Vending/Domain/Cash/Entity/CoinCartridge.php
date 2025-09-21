<?php

namespace App\Vending\Domain\Cash\Entity;

use App\Vending\Domain\Cash\Exception\InvalidNumberOfCoinsException;
use App\Vending\Domain\Cash\Exception\NotEnoughNumberOfCoinsException;

class CoinCartridge
{
    private function __construct(
        private Coin $coin,
        private int $quantity
    ) {
    }

    public static function create(float $value, int $quantity): self
    {
        if ($quantity < 1) {
            throw new InvalidNumberOfCoinsException();
        }

        return new self(
            Coin::create($value),
            $quantity
        );
    }

    public function getCoin(): Coin
    {
        return $this->coin;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function add(int $quantity): void
    {
        $this->checkQuantity($quantity);
        $this->quantity += $quantity;
    }

    public function substract(int $quantity): void
    {
        $this->checkQuantity($quantity);

        if ($quantity > $this->quantity) {
            throw new NotEnoughNumberOfCoinsException();
        }

        $this->quantity -= $quantity;
    }

    private function checkQuantity(int $quantity): void
    {
        if ($quantity < 1) {
            throw new InvalidNumberOfCoinsException();
        }
    }
}
