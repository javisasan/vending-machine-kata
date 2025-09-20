<?php

namespace App\VendingMachine\Infrastructure\UI\Controller;

use App\SharedKernel\Infrastructure\Messenger\Bus\QueryBus;
use App\VendingMachine\Application\Query\StatusQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatusController extends AbstractController
{
    public function __construct(
        private QueryBus $messageBus,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        /** @var StatusQueryHandlerResponse $response */
        $response = $this->messageBus->dispatch(
            new StatusQuery()
        );

        return new JsonResponse(
            $response->getProducts()
        );
    }
}
