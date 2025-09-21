<?php

namespace App\Vending\Application\Machine\Query;

use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;

class CreditQueryHandler
{
    public function __construct(private VendingMachineRepositoryInterface $repository)
    {
    }

    public function __invoke(CreditQuery $query): CreditQueryHandlerResponse
    {
        $vendingMachine = $this->repository->get();

        $response = new CreditQueryHandlerResponse($vendingMachine);

        return $response;
    }
}
