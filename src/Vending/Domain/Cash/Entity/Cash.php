<?php

namespace App\Vending\Domain\Cash\Entity;

use App\Vending\Domain\Cash\Exception\NotEnoughChangeException;

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

    public function substract(CoinCartridge $coinCartridge): void
    {
        $coinValue = (string) $coinCartridge->getCoin()->getValue();
        $quantity = $coinCartridge->getQuantity();

        if (in_array($coinValue, array_keys($this->coinCartridgeCollection))) {
            $this->coinCartridgeCollection[$coinValue]->substract($quantity);
        }
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

        return round($total, 2);
    }

    public function calculateExchangeCoinsForValue(float $value): Cash
    {
        $return = new Cash();

        $remainder = (int) (round($value, 2) * 100);

        $coinCartridges = $this->getCoinCartridges();
        krsort($coinCartridges);

        /** @var CoinCartridge $coinCartridge */
        foreach ($coinCartridges as $coinCartridge) {
            $coinValue = (int) ($coinCartridge->getCoin()->getValue() * 100);
            $coinQuantity = $coinCartridge->getQuantity();

            if ($coinQuantity === 0) {
                continue;
            }

            if ($remainder < $coinValue) {
                continue;
            }

            $coinsNeeded = intdiv($remainder, $coinValue);
            $coinsNeeded = $coinQuantity >= $coinsNeeded ? $coinsNeeded : $coinQuantity;

            $return->append(CoinCartridge::create($coinCartridge->getCoin()->getValue(), $coinsNeeded));

            $remainder -= $coinValue * $coinsNeeded;

            if (empty($remainder)) {
                break;
            }
        }

        if ($remainder > 0) {
            throw new NotEnoughChangeException();
        }

        return $return;
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
