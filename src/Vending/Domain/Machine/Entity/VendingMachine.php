<?php

namespace App\Vending\Domain\Machine\Entity;

use App\Vending\Domain\Cash\Dto\CoinCartridgeDto;
use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Machine\Dto\ServiceDto;

class VendingMachine
{
    private function __construct(
        private array $inventory,
        private Cash $exchange,
        private Cash $credit
    ) {
    }

    public static function create(
    ): self {
        return new self(
            [],
            new Cash(),
            new Cash()
        );
    }

    public static function fromService(ServiceDto $serviceDto): self
    {
        $inventory = [];
        $exchange = new Cash();

        foreach ($serviceDto->getInventory() as $inventoryItem) {
            // todo
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

    public function getInventory(): array
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

    /*
    public function addExchange(CoinCartridge $coinCartridge): void
    {
        $this->exchange->append($coinCartridge);
    }
     */
}
