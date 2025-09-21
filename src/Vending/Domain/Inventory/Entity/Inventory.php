<?php

namespace App\Vending\Domain\Inventory\Entity;

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

    public function getQuantityForItemSelector(string $selector): int
    {
        if (!in_array($selector, array_keys($this->inventoryItemCollection))) {
            return 0;
        }

        return $this->inventoryItemCollection[$selector]->getQuantity();
    }
}
