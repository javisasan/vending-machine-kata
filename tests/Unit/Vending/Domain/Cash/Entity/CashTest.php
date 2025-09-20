<?php

namespace Tests\Unit\Vending\Domain\Cash\Entity;

use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Cash\Exception\InvalidCoinAmountException;
use PHPUnit\Framework\TestCase;

class CashTest extends TestCase
{
    public function testCanCreateCoin(): void
    {
        $value = 0.25;
        $quantity = 3;

        $coinCartridge = CoinCartridge::create($value, $quantity);

        $sut = new Cash();
        $sut->append($coinCartridge);

        $data = $sut->getCoinCartridges();

        $this->assertSame(1, count($data));
        $this->assertSame($quantity, $sut->getQuantityForCoinValue($value));
        $this->assertSame(0, $sut->getQuantityForCoinValue(0.1));
    }

    public function testCanNotCreateCoinValueWithInvalidValue(): void
    {
        $this->expectException(InvalidCoinAmountException::class);

        Coin::create(0.15);
    }
}
