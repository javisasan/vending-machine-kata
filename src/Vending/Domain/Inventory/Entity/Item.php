<?php

namespace App\Vending\Domain\Inventory\Entity;

use App\Vending\Domain\Inventory\Entity\ValueObject\ItemSelector;

class Item
{
    private function __construct(
        private ItemSelector $selector
    ) {
    }

    public static function create(string $selector): self
    {
        return new self(
            new ItemSelector($selector)
        );
    }

    public function getSelector(): ItemSelector
    {
        return $this->selector;
    }
}
