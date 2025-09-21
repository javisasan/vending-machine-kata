<?php

namespace App\Vending\Application\Machine\Query;

use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;

class RefundQueryHandler
{
    public function __construct(private VendingMachineRepositoryInterface $repository)
    {
    }

    public function __invoke(RefundQuery $query): RefundQueryHandlerResponse
    {
        $vendingMachine = $this->repository->get();

        $response = new RefundQueryHandlerResponse($vendingMachine);

        return $response;
    }
}
