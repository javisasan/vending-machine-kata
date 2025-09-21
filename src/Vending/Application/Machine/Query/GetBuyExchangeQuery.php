<?php

namespace App\Vending\Application\Machine\Query;

class GetBuyExchangeQuery
{
    public function __construct(private string $selector)
    {
    }

    public function getSelector(): string
    {
        return $this->selector;
    }
}
