<?php

namespace App\Vending\Application\Machine\Query;

use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Machine\Entity\VendingMachine;

class RefundQueryHandlerResponse
{
    public function __construct(private VendingMachine $vendingMachine)
    {
    }

    public function toArray(): array
    {
        return [
            'coins' => $this->getCreditToArray($this->vendingMachine->getCredit()),
        ];
    }

    private function getCreditToArray(Cash $credit): array
    {
        $coins = [];

        /** @var CoinCartridge $coinCartridge */
        foreach ($credit->getCoinCartridges() as $coinCartridge) {
            $quantity = $coinCartridge->getQuantity();
            while ($quantity > 0) {
                $coins[] = $coinCartridge->getCoin()->getValue();
                $quantity --;
            }
        }

        return $coins;
    }
}
