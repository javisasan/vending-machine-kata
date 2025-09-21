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

    public function addCoin(Coin $coin): void
    {
        $coinCartridge = $this->getCoinCartridge($coin->getValue());

        if ($coinCartridge) {
            $coinCartridge->add(1);

            return;
        }

        $coinCartridge = CoinCartridge::create($coin->getValue(), 1);

        $this->append($coinCartridge);
    }

    public function getTotalAmount(): float
    {
        $total = 0;

        foreach ($this->coinCartridgeCollection as $cartridgeCollection) {
            $total += $cartridgeCollection->getCoin()->getValue() * $cartridgeCollection->getQuantity();
        }

        return $total;
    }

    private function getCoinCartridge(float $value): ?CoinCartridge
    {
        $coinValue = (string) $value;

        if (!in_array($coinValue, array_keys($this->coinCartridgeCollection))) {
            return null;
        }

        return $this->coinCartridgeCollection[$coinValue];
    }
}
