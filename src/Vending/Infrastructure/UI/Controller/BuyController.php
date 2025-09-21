<?php

namespace App\Vending\Infrastructure\UI\Controller;

use App\SharedKernel\Infrastructure\Messenger\Bus\QueryBus;
use App\Vending\Application\Machine\Command\BuyItemCommand;
use App\Vending\Application\Machine\Query\GetBuyExchangeQuery;
use App\Vending\Application\Machine\Query\GetBuyExchangeQueryHandlerResponse;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;

class BuyController extends AbstractController
{
    public function __construct(
        private QueryBus $messageBus,
        private MessageBusInterface $commandBus
    ) {
    }

    public function __invoke(string $selector): JsonResponse
    {
        try {
            /** @var GetBuyExchangeQueryHandlerResponse $response */
            $response = $this->messageBus->dispatch(
                new GetBuyExchangeQuery($selector)
            );

            $this->commandBus->dispatch(
                new BuyItemCommand($selector)
            );
        } catch (Exception $e) {
            return new JsonResponse([
                'error_code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }

        return new JsonResponse(
            $response->toArray()
        );
    }
}
