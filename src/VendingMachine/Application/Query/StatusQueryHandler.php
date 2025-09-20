<?php

namespace App\VendingMachine\Application\Query;

class StatusQueryHandler
{
    public function __construct(
    ) {
    }

    public function __invoke(StatusQuery $query): StatusQueryHandlerResponse
    {
        $response = new StatusQueryHandlerResponse();

        return $response;
    }
}
