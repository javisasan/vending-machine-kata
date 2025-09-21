<?php

namespace Tests\Unit\Vending\Domain\Inventory\Entity;

use App\Vending\Domain\Inventory\Entity\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testCanCreateItem(): void
    {
        $sut = Item::create('water');

        $this->assertSame('water', $sut->getSelector()->getName());
    }
}
