<?php

namespace App\Vending\Application\Machine\Query;

use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Inventory\Entity\Inventory;
use App\Vending\Domain\Inventory\Entity\InventoryItem;
use App\Vending\Domain\Machine\Entity\VendingMachine;

class StatusQueryHandlerResponse
{
    public function __construct(private VendingMachine $vendingMachine)
    {
    }

    public function toArray(): array
    {
        return [
            'inventory' => $this->getInventoryToArray($this->vendingMachine->getInventory()),
            'exchange' => $this->getExchangeToArray($this->vendingMachine->getExchange()),
            'credit' => $this->vendingMachine->getCredit(),
        ];
    }

    private function getInventoryToArray(Inventory $inventory): array
    {
        $items = [];

        /** @var InventoryItem $inventoryItem */
        foreach ($inventory->getInventoryItems() as $inventoryItem) {
            $items[] = [
                'selector' => $inventoryItem->getItem()->getSelector()->getName(),
                'price' => $inventoryItem->getPrice()->getValue(),
                'quantity' => $inventoryItem->getQuantity(),
            ];
        }

        return $items;
    }

    private function getExchangeToArray(Cash $exchange): array
    {
        $coins = [];

        /** @var CoinCartridge $coinCartridge */
        foreach ($exchange->getCoinCartridges() as $coinCartridge) {
            $coins[(string) $coinCartridge->getCoin()->getValue()] = $coinCartridge->getQuantity();
        }

        return $coins;
    }
}
