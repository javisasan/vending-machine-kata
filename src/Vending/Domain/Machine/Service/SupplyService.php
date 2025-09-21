<?php

namespace App\Vending\Domain\Machine\Service;

use App\Vending\Domain\Cash\Dto\CoinCartridgeDto;
use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Inventory\Dto\InventoryItemDto;
use App\Vending\Domain\Inventory\Entity\Inventory;
use App\Vending\Domain\Inventory\Entity\InventoryItem;
use App\Vending\Domain\Machine\Entity\VendingMachine;

class SupplyService implements SupplyServiceInterface
{
    /**
     * @param InventoryItemDto[] $inventory
     * @param CoinCartridgeDto[] $exchange
     */
    public function createVendingMachineFromService(array $inventoryItems, array $coinCartridges): VendingMachine
    {
        $inventory = new Inventory();
        $exchange = new Cash();

        /** @var InventoryItemDto $inventoryItem */
        foreach ($inventoryItems as $inventoryItem) {
            $inventoryItem = InventoryItem::create(
                $inventoryItem->getSelector(),
                $inventoryItem->getPrice(),
                $inventoryItem->getQuantity()
            );
            $inventory->append($inventoryItem);
        }

        /** @var CoinCartridgeDto $exchangeItem */
        foreach ($coinCartridges as $exchangeItem) {
            $coinCartridge = CoinCartridge::create($exchangeItem->getValue(), $exchangeItem->getQuantity());
            $exchange->append($coinCartridge);
        }

        return VendingMachine::fromService(
            $inventory,
            $exchange,
        );
    }
}
