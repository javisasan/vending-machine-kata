<?php

namespace Tests\Unit\Vending\Domain\Inventory\Entity;

use App\Vending\Domain\Inventory\Entity\InventoryItem;
use App\Vending\Domain\Inventory\Exception\InvalidNumberOfItemsException;
use App\Vending\Domain\Inventory\Exception\NotEnoughNumberOfItemsException;
use PHPUnit\Framework\TestCase;

class InventoryItemTest extends TestCase
{
    public function testCanCreateInventoryItemAndAddAndSubstractQuantity(): void
    {
        $selector = 'water';
        $price = 0.65;
        $quantity = 1;

        $sut = InventoryItem::create($selector, $price, $quantity);

        $this->assertSame($selector, $sut->getItem()->getSelector()->getName());
        $this->assertSame($price, $sut->getPrice()->getValue());
        $this->assertSame($quantity, $sut->getQuantity());

        $sut->add(1);
        $this->assertSame($quantity + 1, $sut->getQuantity());

        $sut->substract(2);
        $this->assertSame($quantity - 1, $sut->getQuantity());
    }

    public function testCanNotSubstractMoreItemsThanExisting(): void
    {
        $this->expectException(NotEnoughNumberOfItemsException::class);

        $selector = 'water';
        $price = 0.65;
        $quantity = 1;

        $sut = InventoryItem::create($selector, $price, $quantity);

        $sut->substract(2);
    }

    public function testCanNotCreateInventoryItemWithNegativeQuantity(): void
    {
        $this->expectException(InvalidNumberOfItemsException::class);

        $selector = 'water';
        $price = 0.65;
        $quantity = -4;

        $sut = InventoryItem::create($selector, $price, $quantity);

        $this->assertSame($selector, $sut->getItem()->getSelector()->getName());
        $this->assertSame($price, $sut->getPrice()->getValue());
        $this->assertSame($quantity, $sut->getQuantity());
    }
}
