<?php

namespace App\Vending\Domain\Inventory\Entity;

use App\Vending\Domain\Inventory\Exception\ItemDoesNotExistException;

class Inventory
{
    /** @var InventoryItem[] */
    private array $inventoryItemCollection;

    public function __construct()
    {
        $this->inventoryItemCollection = [];
    }

    public function getInventoryItems(): array
    {
        return $this->inventoryItemCollection;
    }

    public function append(InventoryItem $inventoryItem): void
    {
        $selector = $inventoryItem->getItem()->getSelector()->getName();
        $quantity = $inventoryItem->getQuantity();

        if (in_array($selector, array_keys($this->inventoryItemCollection))) {
            $this->inventoryItemCollection[$selector]->add($quantity);

            return;
        }

        $this->inventoryItemCollection[$selector] = $inventoryItem;
    }

    public function substractItem(string $selector): void
    {
        $inventoryItem = $this->getInventoryItem($selector);
        $inventoryItem->substract(1);
    }

    public function getPriceForItemSelector(string $selector): float
    {
        return $this->getInventoryItem($selector)->getPrice()->getValue();
    }

    public function getQuantityForItemSelector(string $selector): int
    {
        return $this->getInventoryItem($selector)->getQuantity();
    }

    public function getInventoryItem(string $selector): InventoryItem
    {
        if (!in_array($selector, array_keys($this->inventoryItemCollection))) {
            throw new ItemDoesNotExistException();
        }

        return $this->inventoryItemCollection[$selector];
    }
}
