<?php

namespace App\Vending\Application\Machine\Query;

use App\Vending\Domain\Machine\Entity\VendingMachine;

class CreditQueryHandlerResponse
{
    public function __construct(private VendingMachine $vendingMachine)
    {
    }

    public function toArray(): array
    {
        return [
            'credit' => $this->vendingMachine->getCredit()->getTotalAmount(),
        ];
    }
}
