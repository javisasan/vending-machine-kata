<?php

namespace App\Vending\Application\Machine\Query;

use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;

class GetBuyAndExchangeQueryHandler
{
    public function __construct(
        private VendingMachineRepositoryInterface $repository
    ) {
    }

    public function __invoke(GetBuyAndExchangeQuery $query): GetBuyAndExchangeQueryHandlerResponse
    {
        $vendingMachine = $this->repository->get();

        $exchange = $vendingMachine->calculateExchangeCoinsForItemBuy($query->getSelector());

        $response = new GetBuyAndExchangeQueryHandlerResponse($query->getSelector(), $exchange);

        return $response;
    }
}
