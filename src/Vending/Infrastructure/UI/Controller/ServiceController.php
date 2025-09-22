<?php

namespace App\Vending\Infrastructure\UI\Controller;

use App\Vending\Application\Machine\Command\ServiceCommand;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class ServiceController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $parameters = json_decode($request->getContent(), true, 5, JSON_THROW_ON_ERROR);

            $this->messageBus->dispatch(
                new ServiceCommand(
                    $parameters['inventory'] ?? [],
                    $parameters['exchange'] ?? []
                )
            );
        } catch (Exception $e) {
            return new JsonResponse([
                'error_code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }

        return new JsonResponse([
            'status' => 'ok'
        ]);
    }
}
