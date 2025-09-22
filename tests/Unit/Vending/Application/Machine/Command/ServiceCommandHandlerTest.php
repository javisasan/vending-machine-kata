<?php

namespace Tests\Unit\Vending\Application\Machine\Command;

use App\Vending\Application\Machine\Command\ServiceCommand;
use App\Vending\Application\Machine\Command\ServiceCommandHandler;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;
use App\Vending\Domain\Machine\Service\SupplyServiceInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class ServiceCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private ObjectProphecy|SupplyServiceInterface $service;
    private ObjectProphecy|VendingMachineRepositoryInterface $repository;
    private ServiceCommandHandler $sut;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->service = $this->prophet->prophesize(SupplyServiceInterface::class);
        $this->repository = $this->prophet->prophesize(VendingMachineRepositoryInterface::class);

        $this->sut = new ServiceCommandHandler(
            $this->service->reveal(),
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

        /** @var MethodProphecy $serviceExpectation */
        $serviceExpectation = $this->service
            //->createVendingMachineFromService($inventory, $exchange)
            ->createVendingMachineFromService(Argument::any(), Argument::any())
            ->willReturn($vendingMachine);

        /** @var MethodProphecy $repositoryPersistExpectation */
        $repositoryPersistExpectation = $this->repository
            ->persist($vendingMachine);

        $command = new ServiceCommand([], []);

        ($this->sut)($command);

        $serviceExpectation->shouldBeCalledOnce();
        $repositoryPersistExpectation->shouldBeCalledOnce();

        $this->assertTrue(true);
    }
}
