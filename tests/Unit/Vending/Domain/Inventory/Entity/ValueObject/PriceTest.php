<?php

namespace Tests\Unit\Vending\Domain\Cash\Entity\ValueObject;

use App\Vending\Domain\Inventory\Entity\ValueObject\Price;
use App\Vending\Domain\Inventory\Exception\InvalidPriceValueException;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function testCanCreatePrice(): void
    {
        $price = 10.25;

        $sut = new Price($price);

        $this->assertSame($price, $sut->getValue());
    }

    public function testCanNotCreatePriceWithZeroValue(): void
    {
        $this->expectException(InvalidPriceValueException::class);

        new Price(0);
    }

    public function testCanNotCreatePriceWithNegativeValue(): void
    {
        $this->expectException(InvalidPriceValueException::class);

        new Price(-5.2);
    }
}
