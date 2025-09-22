<?php

namespace Tests\Unit\Vending\Application\Machine\Command;

use App\Vending\Application\Machine\Command\CreditCommand;
use App\Vending\Application\Machine\Command\CreditCommandHandler;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class CreditCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private ObjectProphecy|VendingMachineRepositoryInterface $repository;
    private CreditCommandHandler $sut;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->repository = $this->prophet->prophesize(VendingMachineRepositoryInterface::class);

        $this->sut = new CreditCommandHandler(
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

        /** @var MethodProphecy $repositoryGetExpectation */
        $repositoryGetExpectation = $this->repository
            ->get()
            ->willReturn($vendingMachine);

        /** @var MethodProphecy $repositoryPersistExpectation */
        $repositoryPersistExpectation = $this->repository
            ->persist($vendingMachine);

        $command = new CreditCommand(1.0);

        ($this->sut)($command);

        $repositoryGetExpectation->shouldBeCalledOnce();
        $repositoryPersistExpectation->shouldBeCalledOnce();

        $this->assertEquals(1, $vendingMachine->getCredit()->getTotalAmount());
    }
}
