<?php

namespace App\Vending\Domain\Cash\Entity;

class Cash
{
    /** @var CoinCartridge[] */
    private array $coinCartridgeCollection;

    public function __construct()
    {
        $this->coinCartridgeCollection = [];
    }

    public function getCoinCartridges(): array
    {
        return $this->coinCartridgeCollection;
    }

    public function append(CoinCartridge $coinCartridge): void
    {
        $coinValue = (string) $coinCartridge->getCoin()->getValue();
        $quantity = $coinCartridge->getQuantity();

        if (in_array($coinValue, array_keys($this->coinCartridgeCollection))) {
            $this->coinCartridgeCollection[$coinValue]->add($quantity);

            return;
        }

        $this->coinCartridgeCollection[$coinValue] = $coinCartridge;
    }

    public function getQuantityForCoinValue(float $value): int
    {
        $coinValue = (string) $value;

        if (!in_array($coinValue, array_keys($this->coinCartridgeCollection))) {
            return 0;
        }

        return $this->coinCartridgeCollection[$coinValue]->getQuantity();
    }
}
