<?php

namespace Tests\Unit\Vending\Application\Machine\Query;

use App\Vending\Application\Machine\Query\RefundQueryHandlerResponse;
use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use PHPUnit\Framework\TestCase;

class RefundQueryHandlerResponseTest extends TestCase
{
    public function testCanCreateHandlerResponse(): void
    {
        $exchange = new Cash();
        $exchange->append(CoinCartridge::create(0.25, 2));
        $exchange->append(CoinCartridge::create(0.10, 1));

        $vendingMachine = VendingMachine::create();

        $vendingMachine->addCredit(Coin::create(0.25));
        $vendingMachine->addCredit(Coin::create(0.25));
        $vendingMachine->addCredit(Coin::create(0.1));

        $sut = new RefundQueryHandlerResponse($vendingMachine);

        $response = $sut->toArray();

        $this->assertSame(3, count($response['coins']));
        $this->assertSame(0.25, $response['coins'][0]);
        $this->assertSame(0.25, $response['coins'][1]);
        $this->assertSame(0.10, $response['coins'][2]);
    }
}
