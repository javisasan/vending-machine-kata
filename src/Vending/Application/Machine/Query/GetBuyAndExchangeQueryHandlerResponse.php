<?php

namespace App\Vending\Application\Machine\Query;

use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\CoinCartridge;

class GetBuyAndExchangeQueryHandlerResponse
{
    public function __construct(private string $selector, private Cash $exchange)
    {
    }

    public function toArray(): array
    {
        return [
            'item' => $this->selector,
            'exchange' => $this->getExchangeToArray($this->exchange),
        ];
    }

    private function getExchangeToArray(Cash $exchange): array
    {
        $coins = [];

        /** @var CoinCartridge $coinCartridge */
        foreach ($exchange->getCoinCartridges() as $coinCartridge) {
            $quantity = $coinCartridge->getQuantity();
            while ($quantity > 0) {
                $coins[] = $coinCartridge->getCoin()->getValue();
                $quantity --;
            }
        }

        return $coins;
    }
}
