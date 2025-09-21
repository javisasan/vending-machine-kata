<?php

namespace App\Vending\Domain\Machine\Entity;

use App\Vending\Domain\Cash\Dto\CoinCartridgeDto;
use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Inventory\Dto\InventoryItemDto;
use App\Vending\Domain\Inventory\Entity\Inventory;
use App\Vending\Domain\Inventory\Entity\InventoryItem;
use App\Vending\Domain\Machine\Dto\ServiceDto;

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

    public static function fromService(ServiceDto $serviceDto): self
    {
        $inventory = new Inventory();
        $exchange = new Cash();

        /** @var InventoryItemDto $inventoryItem */
        foreach ($serviceDto->getInventory() as $inventoryItem) {
            $inventoryItem = InventoryItem::create(
                $inventoryItem->getSelector(),
                $inventoryItem->getPrice(),
                $inventoryItem->getQuantity()
            );
            $inventory->append($inventoryItem);
        }

        /** @var CoinCartridgeDto $exchangeItem */
        foreach ($serviceDto->getExchange() as $exchangeItem) {
            $coinCartridge = CoinCartridge::create($exchangeItem->getValue(), $exchangeItem->getQuantity());
            $exchange->append($coinCartridge);
        }

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

    /*
    public function addExchange(CoinCartridge $coinCartridge): void
    {
        $this->exchange->append($coinCartridge);
    }
     */
}
