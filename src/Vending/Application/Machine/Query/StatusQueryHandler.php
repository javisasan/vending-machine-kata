<?php

namespace App\Vending\Application\Machine\Query;

use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;

class StatusQueryHandler
{
    public function __construct(private VendingMachineRepositoryInterface $repository)
    {
    }

    public function __invoke(StatusQuery $query): StatusQueryHandlerResponse
    {
        $vendingMachine = $this->repository->get();

        $response = new StatusQueryHandlerResponse($vendingMachine);

        return $response;
    }
}
