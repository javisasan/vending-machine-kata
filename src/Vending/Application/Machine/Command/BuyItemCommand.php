<?php

namespace App\Vending\Application\Machine\Command;

class BuyItemCommand
{
    public function __construct(private string $selector)
    {
    }

    public function getSelector(): string
    {
        return $this->selector;
    }
}
