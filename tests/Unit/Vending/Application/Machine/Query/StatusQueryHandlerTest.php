<?php

namespace Tests\Unit\Vending\Application\Machine\Query;

use App\Vending\Application\Machine\Query\StatusQuery;
use App\Vending\Application\Machine\Query\StatusQueryHandler;
use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Inventory\Entity\Inventory;
use App\Vending\Domain\Inventory\Entity\InventoryItem;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class StatusQueryHandlerTest extends TestCase
{
    private Prophet $prophet;
    private ObjectProphecy|VendingMachineRepositoryInterface $repository;
    private StatusQueryHandler $sut;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->repository = $this->prophet->prophesize(VendingMachineRepositoryInterface::class);

        $this->sut = new StatusQueryHandler(
            $this->repository->reveal()
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->prophet->checkPredictions();
    }

    public function testCanHandleQuerySuccessfuly(): void
    {
        $expectedResponse = [
            'credit' => 1.25,
            'inventory' => [
                [
                    'selector' => 'water',
                    'price' => 0.65,
                    'quantity' => 2,
                ],
            ],
            'exchange' => [
                '0.25' => 3,
            ],
        ];

        $inventory = new Inventory();
        $inventory->append(InventoryItem::create('water', 0.65, 2));

        $exchange = new Cash();
        $exchange->append(CoinCartridge::create(0.25, 3));

        $vendingMachine = VendingMachine::fromService($inventory, $exchange);
        $vendingMachine->addCredit(Coin::create(0.25));
        $vendingMachine->addCredit(Coin::create(1.0));

        /** @var MethodProphecy $repositoryGetExpectation */
        $repositoryGetExpectation = $this->repository
            ->get()
            ->willReturn($vendingMachine);

        $query = new StatusQuery();

        $response = ($this->sut)($query);

        $repositoryGetExpectation->shouldBeCalledOnce();

        $this->assertEquals($expectedResponse, $response->toArray());
    }
}
