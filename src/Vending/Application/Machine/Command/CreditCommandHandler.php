<?php

namespace App\Vending\Application\Machine\Command;

use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;

class CreditCommandHandler
{
    public function __construct(private VendingMachineRepositoryInterface $repository)
    {
    }

    public function __invoke(CreditCommand $command): void
    {
        $coin = Coin::create($command->getValue());

        $vendingMachine = $this->repository->get();
        $vendingMachine->addCredit($coin);

        $this->repository->persist($vendingMachine);
    }
}
