<?php

namespace App\Vending\Application\Machine\Command;

class ServiceCommand
{
    public function __construct(private array $inventory, private array $exchange)
    {
    }

    public function getInventory(): array
    {
        return $this->inventory;
    }

    public function getExchange(): array
    {
        return $this->exchange;
    }
}
