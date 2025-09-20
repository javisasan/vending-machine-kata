<?php

namespace App\Vending\Application\Machine\Query;

use App\Vending\Domain\Machine\Entity\VendingMachine;

class StatusQueryHandlerResponse
{
    public function __construct(private VendingMachine $vendingMachine)
    {
    }

    public function toArray(): array
    {
        return [
            'inventory' => $this->vendingMachine->getInventory(),
            'exchange' => $this->vendingMachine->getExchange(),
            'credit' => $this->vendingMachine->getCredit(),
        ];
    }
}
