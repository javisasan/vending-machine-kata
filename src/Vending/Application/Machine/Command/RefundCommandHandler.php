<?php

namespace App\Vending\Application\Machine\Command;

use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;

class RefundCommandHandler
{
    public function __construct(private VendingMachineRepositoryInterface $repository)
    {
    }

    public function __invoke(RefundCommand $command): void
    {
        $vendingMachine = $this->repository->get();
        $vendingMachine->emptyCredit();

        $this->repository->persist($vendingMachine);
    }
}
