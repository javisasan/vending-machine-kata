<?php

namespace Tests\Unit\Vending\Application\Machine\Command;

use App\Vending\Application\Machine\Command\RefundCommand;
use App\Vending\Application\Machine\Command\RefundCommandHandler;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class RefundCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private ObjectProphecy|VendingMachineRepositoryInterface $repository;
    private RefundCommandHandler $sut;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->repository = $this->prophet->prophesize(VendingMachineRepositoryInterface::class);

        $this->sut = new RefundCommandHandler(
            $this->repository->reveal()
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->prophet->checkPredictions();
    }

    public function testCanHandleCommandSuccessfuly(): void
    {
        $vendingMachine = VendingMachine::create();
        $vendingMachine->addCredit(Coin::create(0.25));
        $vendingMachine->addCredit(Coin::create(1.0));

        /** @var MethodProphecy $repositoryGetExpectation */
        $repositoryGetExpectation = $this->repository
            ->get()
            ->willReturn($vendingMachine);

        /** @var MethodProphecy $repositoryPersistExpectation */
        $repositoryPersistExpectation = $this->repository
            ->persist($vendingMachine);

        $command = new RefundCommand();

        ($this->sut)($command);

        $repositoryGetExpectation->shouldBeCalledOnce();
        $repositoryPersistExpectation->shouldBeCalledOnce();

        $this->assertEquals(0.0, $vendingMachine->getCredit()->getTotalAmount());
    }
}
