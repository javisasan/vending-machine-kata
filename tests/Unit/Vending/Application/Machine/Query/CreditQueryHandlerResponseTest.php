<?php

namespace Tests\Unit\Vending\Domain\Inventory\Dto;

use App\Vending\Application\Machine\Query\CreditQueryHandlerResponse;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use PHPUnit\Framework\TestCase;

class CreditQueryHandlerResponseTest extends TestCase
{
    public function testCanCreateHandlerResponse(): void
    {
        $vendingMachine = VendingMachine::create();
        $vendingMachine->addCredit(Coin::create(0.25));
        $vendingMachine->addCredit(Coin::create(0.10));


        $sut = new CreditQueryHandlerResponse($vendingMachine);

        $result = $sut->toArray();

        $this->assertSame(0.35, $result['credit']);
    }
}
