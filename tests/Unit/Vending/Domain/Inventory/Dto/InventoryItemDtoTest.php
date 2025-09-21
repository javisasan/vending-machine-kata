<?php

namespace Tests\Unit\Vending\Domain\Inventory\Dto;

use App\Vending\Domain\Inventory\Dto\InventoryItemDto;
use PHPUnit\Framework\TestCase;

class InventoryItemDtoTest extends TestCase
{
    public function testCanCreateDto(): void
    {
        $selector = 'water';
        $price = 0.65;
        $quantity = 3;

        $sut = new InventoryItemDto($selector, $price, $quantity);

        $this->assertSame($selector, $sut->getSelector());
        $this->assertSame($price, $sut->getPrice());
        $this->assertSame($quantity, $sut->getQuantity());
    }
}
