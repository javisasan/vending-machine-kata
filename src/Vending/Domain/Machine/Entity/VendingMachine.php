<?php

namespace App\Vending\Domain\Machine\Entity;

use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Inventory\Entity\Inventory;
use App\Vending\Domain\Machine\Exception\InsufficientCreditException;
use App\Vending\Domain\Machine\Exception\NotEnoughChangeException;
use App\Vending\Domain\Machine\Exception\ProductIsDepletedException;

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

    public static function fromService(Inventory $inventory, Cash $exchange): self
    {
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

    public function emptyCredit(): void
    {
        $this->credit = new Cash();
    }

    private function mergeAllMachineCoins(): Cash
    {
        $totalCoins = new Cash();

        /** @var CoinCartridge $coinCartridge */
        foreach ($this->exchange->getCoinCartridges() as $coinCartridge) {
            $totalCoins->append(CoinCartridge::create($coinCartridge->getCoin()->getValue(), $coinCartridge->getQuantity()));
        }

        /** @var CoinCartridge $coinCartridge */
        foreach ($this->credit->getCoinCartridges() as $coinCartridge) {
            $totalCoins->append(CoinCartridge::create($coinCartridge->getCoin()->getValue(), $coinCartridge->getQuantity()));
        }

        return $totalCoins;
    }

    public function buyItem(string $itemSelector): void
    {
        $inventoryItem = $this->inventory->getInventoryItem($itemSelector);

        if (1 > $inventoryItem->getQuantity()) {
            throw new ProductIsDepletedException();
        }

        $this->inventory->substractItem($itemSelector);

        $exchangeForUser = $this->calculateExchangeForItem($itemSelector);

        $this->exchange = $this->mergeAllMachineCoins();
        $this->emptyCredit();

        foreach ($exchangeForUser->getCoinCartridges() as $coinCartridge) {
            $this->exchange->substract($coinCartridge);
        }
    }

    public function calculateExchangeForItem(string $itemSelector): Cash
    {
        $return = new Cash();
        $itemPrice = $this->inventory->getPriceForItemSelector($itemSelector);
        $remainder = (int) (round($this->credit->getTotalAmount() - $itemPrice, 2) * 100);

        if (0 > $remainder) {
            throw new InsufficientCreditException();
        }

        $machineCoins = $this->mergeAllMachineCoins()->getCoinCartridges();
        krsort($machineCoins);

        /** @var CoinCartridge $machineCoin */
        foreach ($machineCoins as $machineCoin) {
            $coinValue = (int) ($machineCoin->getCoin()->getValue() * 100);
            $coinQuantity = $machineCoin->getQuantity();

            if ($coinQuantity === 0) {
                continue;
            }

            if ($remainder < $coinValue) {
                continue;
            }

            $coinsNeeded = intdiv($remainder, $coinValue);
            $coinsNeeded = $coinQuantity >= $coinsNeeded ? $coinsNeeded : $coinQuantity;

            $return->append(CoinCartridge::create($machineCoin->getCoin()->getValue(), $coinsNeeded));

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
}
