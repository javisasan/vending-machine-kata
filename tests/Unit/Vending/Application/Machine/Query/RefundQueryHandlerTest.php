<?php

namespace Tests\Unit\Vending\Application\Machine\Query;

use App\Vending\Application\Machine\Query\RefundQuery;
use App\Vending\Application\Machine\Query\RefundQueryHandler;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class RefundQueryHandlerTest extends TestCase
{
    private Prophet $prophet;
    private ObjectProphecy|VendingMachineRepositoryInterface $repository;
    private RefundQueryHandler $sut;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->repository = $this->prophet->prophesize(VendingMachineRepositoryInterface::class);

        $this->sut = new RefundQueryHandler(
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
            'coins' => [0.25, 1.0],
        ];

        $vendingMachine = VendingMachine::create();
        $vendingMachine->addCredit(Coin::create(0.25));
        $vendingMachine->addCredit(Coin::create(1.0));

        /** @var MethodProphecy $repositoryGetExpectation */
        $repositoryGetExpectation = $this->repository
            ->get()
            ->willReturn($vendingMachine);

        $query = new RefundQuery();

        $response = ($this->sut)($query);

        $repositoryGetExpectation->shouldBeCalledOnce();

        $this->assertEquals($expectedResponse, $response->toArray());
    }
}
