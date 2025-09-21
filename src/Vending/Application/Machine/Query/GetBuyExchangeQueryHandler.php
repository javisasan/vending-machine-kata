<?php

namespace App\Vending\Application\Machine\Query;

use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;

class GetBuyExchangeQueryHandler
{
    public function __construct(
        private VendingMachineRepositoryInterface $repository
    ) {
    }

    public function __invoke(GetBuyExchangeQuery $query): GetBuyExchangeQueryHandlerResponse
    {
        $vendingMachine = $this->repository->get();

        $exchange = $vendingMachine->calculateExchangeForItem($query->getSelector());

        $response = new GetBuyExchangeQueryHandlerResponse($query->getSelector(), $exchange);

        return $response;
    }
}
