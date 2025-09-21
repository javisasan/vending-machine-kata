<?php

namespace App\Vending\Infrastructure\UI\Controller;

use App\SharedKernel\Infrastructure\Messenger\Bus\QueryBus;
use App\Vending\Application\Machine\Command\RefundCommand;
use App\Vending\Application\Machine\Query\RefundQuery;
use App\Vending\Application\Machine\Query\RefundQueryHandlerResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;

class RefundController extends AbstractController
{
    public function __construct(
        private QueryBus $messageBus,
        private MessageBusInterface $commandBus
    ) {
    }

    public function __invoke(): JsonResponse
    {
        /** @var RefundQueryHandlerResponse $response */
        $response = $this->messageBus->dispatch(
            new RefundQuery()
        );

        $this->commandBus->dispatch(
            new RefundCommand()
        );

        return new JsonResponse(
            $response->toArray()
        );
    }
}
