<?php

namespace App\Vending\Application\Machine\Query;

use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Machine\Entity\VendingMachine;

class StatusQueryHandlerResponse
{
    public function __construct(private VendingMachine $vendingMachine)
    {
    }

    public function toArray(): array
    {
        return [
            'inventory' => $this->vendingMachine->getInventory(),
            'exchange' => $this->getExchangeToArray($this->vendingMachine->getExchange()),
            'credit' => $this->vendingMachine->getCredit(),
        ];
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
