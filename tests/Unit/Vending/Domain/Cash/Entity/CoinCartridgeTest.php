<?php

namespace Tests\Unit\Vending\Domain\Cash\Entity;

use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Cash\Exception\InvalidNumberOfCoinsException;
use App\Vending\Domain\Cash\Exception\NotEnoughNumberOfCoinsException;
use PHPUnit\Framework\TestCase;

class CoinCartridgeTest extends TestCase
{
    public function testCanCreateCoinCartridge(): void
    {
        $sut = CoinCartridge::create(0.25, 5);

        $this->assertSame(0.25, $sut->getCoin()->getValue());
        $this->assertSame(5, $sut->getQuantity());
    }

    public function testCanNotCreateCoinCartridgeWithQuantityZero(): void
    {
        $this->expectException(InvalidNumberOfCoinsException::class);

        CoinCartridge::create(0.25, 0);
    }

    public function testCanNotCreateCoinCartridgeWithNegativeQuantity(): void
    {
        $this->expectException(InvalidNumberOfCoinsException::class);

        CoinCartridge::create(0.25, -1);
    }

    public function testCanCreateCoinCartridgeAndAddPositiveValue(): void
    {
        $sut = CoinCartridge::create(0.25, 5);
        $sut->add(5);

        $this->assertSame(10, $sut->getQuantity());
    }

    public function testCanCreateCoinCartridgeAndSubstractPositiveValue(): void
    {
        $sut = CoinCartridge::create(0.25, 5);
        $sut->substract(5);

        $this->assertSame(0, $sut->getQuantity());
    }

    public function testCanNotCreateCoinCartridgeAndAddNegativeValue(): void
    {
        $this->expectException(InvalidNumberOfCoinsException::class);

        $sut = CoinCartridge::create(0.25, 5);
        $sut->add(-1);
    }

    public function testCanNotCreateCoinCartridgeAndSubstractNegativeValue(): void
    {
        $this->expectException(InvalidNumberOfCoinsException::class);

        $sut = CoinCartridge::create(0.25, 5);
        $sut->substract(-1);
    }

    public function testCanNotCreateCoinCartridgeAndSubstractMoreCoinsThanExisting(): void
    {
        $this->expectException(NotEnoughNumberOfCoinsException::class);

        $sut = CoinCartridge::create(0.25, 5);
        $sut->substract(6);
    }
}
