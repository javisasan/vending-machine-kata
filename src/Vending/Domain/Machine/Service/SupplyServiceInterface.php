<?php

namespace App\Vending\Domain\Machine\Service;

use App\Vending\Domain\Machine\Entity\VendingMachine;

interface SupplyServiceInterface
{
    /**
     * @param InventoryItemDto[] $inventory
     * @param CoinCartridgeDto[] $exchange
     */
    public function createVendingMachineFromService(array $inventory, array $exchange): VendingMachine;
}
