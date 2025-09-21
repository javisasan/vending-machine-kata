<?php

namespace App\Vending\Application\Machine\Command;

use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;

class BuyItemCommandHandler
{
    public function __construct(private VendingMachineRepositoryInterface $repository)
    {
    }

    public function __invoke(BuyItemCommand $command): void
    {
        $vendingMachine = $this->repository->get();

        $vendingMachine->buyItem($command->getSelector());

        $this->repository->persist($vendingMachine);
    }
}
