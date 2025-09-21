<?php

namespace Tests\Unit\Vending\Domain\Cash\Entity;

use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use PHPUnit\Framework\TestCase;

class CashTest extends TestCase
{
    public function testCanCreateCash(): void
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

    public function testCanAddCoinToEmptyCash(): void
    {
        $coinTen = Coin::create(0.1);
        $coinTwentyFive = Coin::create(0.25);

        $sut = new Cash();

        $sut->addCoin($coinTen);
        $sut->addCoin($coinTwentyFive);
        $sut->addCoin($coinTwentyFive);

        $data = $sut->getCoinCartridges();

        $this->assertSame(2, count($data));
        $this->assertSame(0, $sut->getQuantityForCoinValue(0.05));
        $this->assertSame(1, $sut->getQuantityForCoinValue(0.1));
        $this->assertSame(2, $sut->getQuantityForCoinValue(0.25));
        $this->assertSame(0.6, $sut->getTotalAmount());
    }
}
