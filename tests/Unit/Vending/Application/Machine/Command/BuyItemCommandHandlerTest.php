<?php

namespace Tests\Unit\Vending\Application\Machine\Command;

use App\Vending\Application\Machine\Command\BuyItemCommand;
use App\Vending\Application\Machine\Command\BuyItemCommandHandler;
use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Inventory\Entity\Inventory;
use App\Vending\Domain\Inventory\Entity\InventoryItem;
use App\Vending\Domain\Inventory\Exception\ItemDoesNotExistException;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use App\Vending\Domain\Machine\Exception\InsufficientCreditException;
use App\Vending\Domain\Machine\Exception\NotEnoughChangeException;
use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class BuyItemCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private ObjectProphecy|VendingMachineRepositoryInterface $repository;
    private BuyItemCommandHandler $sut;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->repository = $this->prophet->prophesize(VendingMachineRepositoryInterface::class);

        $this->sut = new BuyItemCommandHandler(
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
        $inventory = new Inventory();
        $inventory->append(InventoryItem::create('water', 0.65, 2));
        $exchange = new Cash();

        $vendingMachine = VendingMachine::fromService($inventory, $exchange);
        $vendingMachine->addCredit(Coin::create(0.25));
        $vendingMachine->addCredit(Coin::create(0.25));
        $vendingMachine->addCredit(Coin::create(0.10));
        $vendingMachine->addCredit(Coin::create(0.05));

        /** @var MethodProphecy $repositoryGetExpectation */
        $repositoryGetExpectation = $this->repository
            ->get()
            ->willReturn($vendingMachine);

        /** @var MethodProphecy $repositoryPersistExpectation */
        $repositoryPersistExpectation = $this->repository
            ->persist($vendingMachine);

        $command = new BuyItemCommand('water');

        ($this->sut)($command);

        $repositoryGetExpectation->shouldBeCalledOnce();
        $repositoryPersistExpectation->shouldBeCalledOnce();

        $this->assertEquals(1, $vendingMachine->getInventory()->getQuantityForItemSelector('water'));
    }

    public function testCanNotHandleCommandWhenNoCredit(): void
    {
        $inventory = new Inventory();
        $inventory->append(InventoryItem::create('water', 0.65, 2));
        $exchange = new Cash();

        $vendingMachine = VendingMachine::fromService($inventory, $exchange);

        /** @var MethodProphecy $repositoryGetExpectation */
        $repositoryGetExpectation = $this->repository
            ->get()
            ->willReturn($vendingMachine);

        /** @var MethodProphecy $repositoryPersistExpectation */
        $repositoryPersistExpectation = $this->repository
            ->persist($vendingMachine);

        $command = new BuyItemCommand('water');

        try {
            ($this->sut)($command);
        } catch (Exception $e) {
        }

        $repositoryGetExpectation->shouldBeCalledOnce();
        $repositoryPersistExpectation->shouldNotBeCalled();

        $this->assertTrue($e instanceof InsufficientCreditException);
    }

    public function testCanNotHandleCommandWhenNoItem(): void
    {
        $inventory = new Inventory();
        $exchange = new Cash();

        $vendingMachine = VendingMachine::fromService($inventory, $exchange);

        /** @var MethodProphecy $repositoryGetExpectation */
        $repositoryGetExpectation = $this->repository
            ->get()
            ->willReturn($vendingMachine);

        /** @var MethodProphecy $repositoryPersistExpectation */
        $repositoryPersistExpectation = $this->repository
            ->persist($vendingMachine);

        $command = new BuyItemCommand('water');

        try {
            ($this->sut)($command);
        } catch (Exception $e) {
        }

        $repositoryGetExpectation->shouldBeCalledOnce();
        $repositoryPersistExpectation->shouldNotBeCalled();

        $this->assertTrue($e instanceof ItemDoesNotExistException);
    }

    public function testCanNotHandleCommandWhenNoChange(): void
    {
        $inventory = new Inventory();
        $inventory->append(InventoryItem::create('water', 0.65, 2));
        $exchange = new Cash();

        $vendingMachine = VendingMachine::fromService($inventory, $exchange);
        $vendingMachine->addCredit(Coin::create(1.0));

        /** @var MethodProphecy $repositoryGetExpectation */
        $repositoryGetExpectation = $this->repository
            ->get()
            ->willReturn($vendingMachine);

        /** @var MethodProphecy $repositoryPersistExpectation */
        $repositoryPersistExpectation = $this->repository
            ->persist($vendingMachine);

        $command = new BuyItemCommand('water');

        try {
            ($this->sut)($command);
        } catch (Exception $e) {
        }

        $repositoryGetExpectation->shouldBeCalledOnce();
        $repositoryPersistExpectation->shouldNotBeCalled();

        $this->assertTrue($e instanceof NotEnoughChangeException);
    }
}
