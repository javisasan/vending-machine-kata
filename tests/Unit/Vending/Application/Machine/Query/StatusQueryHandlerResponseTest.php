<?php

namespace Tests\Unit\Vending\Application\Machine\Query;

use App\Vending\Application\Machine\Query\StatusQueryHandlerResponse;
use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Inventory\Entity\Inventory;
use App\Vending\Domain\Inventory\Entity\InventoryItem;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use PHPUnit\Framework\TestCase;

class StatusQueryHandlerResponseTest extends TestCase
{
    public function testCanCreateHandlerResponse(): void
    {
        $expectedResponse = [
            'credit' => 0.6,
            'inventory' => [
                [
                    'selector' => 'water',
                    'price' => 0.65,
                    'quantity' => 4,
                ],
                [
                    'selector' => 'juice',
                    'price' => 1.0,
                    'quantity' => 5,
                ],
            ],
            'exchange' => [
                '0.25' => 2,
                '0.1' => 1,
            ],
        ];

        $inventory = new Inventory();
        $inventory->append(InventoryItem::create('water', 0.65, 4));
        $inventory->append(InventoryItem::create('juice', 1.0, 5));

        $exchange = new Cash();
        $exchange->append(CoinCartridge::create(0.25, 2));
        $exchange->append(CoinCartridge::create(0.10, 1));

        $vendingMachine = VendingMachine::fromService($inventory, $exchange);

        $vendingMachine->addCredit(Coin::create(0.25));
        $vendingMachine->addCredit(Coin::create(0.25));
        $vendingMachine->addCredit(Coin::create(0.1));

        $sut = new StatusQueryHandlerResponse($vendingMachine);

        $response = $sut->toArray();

        $this->assertSame($expectedResponse, $response);
    }
}
