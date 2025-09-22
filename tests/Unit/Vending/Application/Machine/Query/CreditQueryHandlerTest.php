<?php

namespace Tests\Unit\Vending\Application\Machine\Query;

use App\Vending\Application\Machine\Query\CreditQuery;
use App\Vending\Application\Machine\Query\CreditQueryHandler;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class CreditQueryHandlerTest extends TestCase
{
    private Prophet $prophet;
    private ObjectProphecy|VendingMachineRepositoryInterface $repository;
    private CreditQueryHandler $sut;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->repository = $this->prophet->prophesize(VendingMachineRepositoryInterface::class);

        $this->sut = new CreditQueryHandler(
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
        $expectedResponse = ['credit' => 1.0];

        $vendingMachine = VendingMachine::create();
        $vendingMachine->addCredit(Coin::create(1.0));

        /** @var MethodProphecy $repositoryGetExpectation */
        $repositoryGetExpectation = $this->repository
            ->get()
            ->willReturn($vendingMachine);

        $query = new CreditQuery();

        $response = ($this->sut)($query);

        $repositoryGetExpectation->shouldBeCalledOnce();

        $this->assertEquals($expectedResponse, $response->toArray());
    }
}
