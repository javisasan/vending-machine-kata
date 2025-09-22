<?php

namespace Tests\Unit\Vending\Application\Machine\Query;

use App\Vending\Application\Machine\Query\GetBuyAndExchangeQuery;
use App\Vending\Application\Machine\Query\GetBuyAndExchangeQueryHandler;
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

class GetBuyAndExchangeQueryHandlerTest extends TestCase
{
    private Prophet $prophet;
    private ObjectProphecy|VendingMachineRepositoryInterface $repository;
    private GetBuyAndExchangeQueryHandler $sut;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->repository = $this->prophet->prophesize(VendingMachineRepositoryInterface::class);

        $this->sut = new GetBuyAndExchangeQueryHandler(
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
            'item' => 'water',
            'exchange' => [0.25, 0.1],
        ];

        $inventory = new Inventory();
        $inventory->append(InventoryItem::create('water', 0.65, 1));

        $exchange = new Cash();
        $exchange->append(CoinCartridge::create(0.25, 5));
        $exchange->append(CoinCartridge::create(0.10, 5));

        $vendingMachine = VendingMachine::fromService($inventory, $exchange);
        $vendingMachine->addCredit(Coin::create(1.0));

        /** @var MethodProphecy $repositoryGetExpectation */
        $repositoryGetExpectation = $this->repository
            ->get()
            ->willReturn($vendingMachine);

        $query = new GetBuyAndExchangeQuery('water');

        $response = ($this->sut)($query);

        $repositoryGetExpectation->shouldBeCalledOnce();

        $this->assertEquals($expectedResponse, $response->toArray());
    }
}
