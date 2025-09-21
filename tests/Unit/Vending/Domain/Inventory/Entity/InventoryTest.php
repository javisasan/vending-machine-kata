<?php

namespace Tests\Unit\Vending\Domain\Inventory\Entity;

use App\Vending\Domain\Inventory\Entity\Inventory;
use App\Vending\Domain\Inventory\Entity\InventoryItem;
use App\Vending\Domain\Inventory\Exception\ItemDoesNotExistException;
use PHPUnit\Framework\TestCase;

class InventoryTest extends TestCase
{
    public function testCanCreateEmptyInventory(): void
    {
        $sut = new Inventory();

        $this->assertEmpty($sut->getInventoryItems());
    }

    public function testCanCreateEmptyInventoryAndAppendItems(): void
    {
        $selector = 'juice';
        $price = 1.0;
        $quantity = 1;

        $inventoryItem = InventoryItem::create($selector, $price, $quantity);

        $sut = new Inventory();
        $sut->append($inventoryItem);

        $this->assertSame($price, $sut->getPriceForItemSelector($selector));
        $this->assertSame($quantity, $sut->getQuantityForItemSelector($selector));
    }

    public function testCanCreateEmptyInventoryAndSubstractItems(): void
    {
        $selector = 'juice';
        $price = 1.0;
        $quantity = 1;

        $inventoryItem = InventoryItem::create($selector, $price, $quantity);

        $sut = new Inventory();
        $sut->append($inventoryItem);
        $sut->substractItem($selector);

        $this->assertSame(0, $sut->getQuantityForItemSelector($selector));
    }

    public function testCanRetrieveInventoryItem(): void
    {
        $selector = 'juice';
        $price = 1.0;
        $quantity = 1;

        $inventoryItem = InventoryItem::create($selector, $price, $quantity);

        $sut = new Inventory();
        $sut->append($inventoryItem);

        $inventoryItem = $sut->getInventoryItem($selector);

        $this->assertSame($selector, $inventoryItem->getItem()->getSelector()->getName());
    }

    public function testCanNotRetrieveUnexistingInventoryItem(): void
    {
        $this->expectException(ItemDoesNotExistException::class);

        $sut = new Inventory();

        $sut->getInventoryItem('dummy');
    }
}
