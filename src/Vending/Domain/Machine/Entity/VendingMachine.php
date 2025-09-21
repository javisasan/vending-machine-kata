<?php

namespace App\Vending\Domain\Machine\Entity;

use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Inventory\Entity\Inventory;

class VendingMachine
{
    private function __construct(
        private Inventory $inventory,
        private Cash $exchange,
        private Cash $credit
    ) {
    }

    public static function create(
    ): self {
        return new self(
            new Inventory(),
            new Cash(),
            new Cash()
        );
    }

    public static function fromService(Inventory $inventory, Cash $exchange): self
    {
        return new self(
            $inventory,
            $exchange,
            new Cash()
        );
    }

    public function getInventory(): Inventory
    {
        return $this->inventory;
    }

    public function getExchange(): Cash
    {
        return $this->exchange;
    }

    public function getCredit(): Cash
    {
        return $this->credit;
    }

    public function addCredit(Coin $coin): void
    {
        $this->credit->addCoin($coin);
    }

    public function emptyCredit(): void
    {
        $this->credit = new Cash();
    }
}
