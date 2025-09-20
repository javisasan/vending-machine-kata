<?php

namespace App\Vending\Domain\Machine\Entity;

class VendingMachine
{
    private array $inventory = [];
    private array $exchange = [];
    private array $credit = [];

    public function getInventory(): array
    {
        return $this->inventory;
    }

    public function getExchange(): array
    {
        return $this->exchange;
    }

    public function getCredit(): array
    {
        return $this->credit;
    }
}
