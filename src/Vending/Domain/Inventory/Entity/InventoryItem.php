<?php

namespace App\Vending\Domain\Inventory\Entity;

use App\Vending\Domain\Inventory\Entity\ValueObject\Price;
use App\Vending\Domain\Inventory\Exception\InvalidNumberOfItemsException;
use App\Vending\Domain\Inventory\Exception\NotEnoughNumberOfItemsException;

class InventoryItem
{
    private function __construct(
        private Item $item,
        private Price $price,
        private int $quantity
    ) {
    }

    public static function create(
        string $selector,
        float $price,
        int $quantity
    ): self {
        if ($quantity < 0) {
            throw new InvalidNumberOfItemsException();
        }

        return new self(
            Item::create($selector),
            new Price($price),
            $quantity
        );
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function getPrice(): Price
    {
        return $this->price;
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
            throw new NotEnoughNumberOfItemsException();
        }

        $this->quantity -= $quantity;
    }

    private function checkQuantity(int $quantity): void
    {
        if ($quantity < 1) {
            throw new InvalidNumberOfItemsException();
        }
    }
}
