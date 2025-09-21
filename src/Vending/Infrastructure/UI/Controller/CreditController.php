<?php

namespace App\Vending\Infrastructure\UI\Controller;

use App\SharedKernel\Infrastructure\Messenger\Bus\QueryBus;
use App\Vending\Application\Machine\Command\CreditCommand;
use App\Vending\Application\Machine\Query\CreditQuery;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;

class CreditController extends AbstractController
{
    public function __construct(
        private QueryBus $messageBus,
        private MessageBusInterface $commandBus
    ) {
    }

    public function __invoke(string $value): JsonResponse
    {
        try {
            $this->commandBus->dispatch(
                new CreditCommand((float) $value)
            );

            $response = $this->messageBus->dispatch(
                new CreditQuery((float) $value)
            );
        } catch (Exception $e) {
            return new JsonResponse([
                'error_code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }

        return new JsonResponse([
            $response->toArray()
        ]);
    }
}
