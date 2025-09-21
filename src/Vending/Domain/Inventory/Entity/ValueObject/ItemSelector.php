<?php

namespace App\Vending\Domain\Inventory\Entity\ValueObject;

use App\Vending\Domain\Inventory\Exception\InvalidProductSelectorException;

class ItemSelector
{
    public const SELECTOR_WATER = 'water';
    public const SELECTOR_JUICE = 'juice';
    public const SELECTOR_SODA = 'soda';

    private const VALID_SELECTORS = [
        self::SELECTOR_WATER,
        self::SELECTOR_JUICE,
        self::SELECTOR_SODA,
    ];

    public function __construct(private string $selector)
    {
        $this->validate($selector);
    }

    public function getName(): string
    {
        return $this->selector;
    }

    private function validate(string $selector): void
    {
        if (!in_array($selector, self::VALID_SELECTORS)) {
            throw new InvalidProductSelectorException();
        }
    }
}
