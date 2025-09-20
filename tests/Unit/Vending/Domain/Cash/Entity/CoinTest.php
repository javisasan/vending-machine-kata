<?php

namespace Tests\Unit\Vending\Domain\Cash\Entity;

use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Cash\Exception\InvalidCoinAmountException;
use PHPUnit\Framework\TestCase;

class CoinTest extends TestCase
{
    public function testCanCreateCoin(): void
    {
        $sut = Coin::create(0.25);

        $this->assertSame(0.25, $sut->getValue());
    }

    public function testCanNotCreateCoinValueWithInvalidValue(): void
    {
        $this->expectException(InvalidCoinAmountException::class);

        Coin::create(0.15);
    }
}
