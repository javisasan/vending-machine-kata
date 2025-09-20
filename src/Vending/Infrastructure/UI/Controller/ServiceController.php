<?php

namespace App\Vending\Infrastructure\UI\Controller;

use App\Vending\Application\Machine\Command\ServiceCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $parameters = json_decode($request->getContent(), true);

        $this->messageBus->dispatch(
            new ServiceCommand(
                $parameters['inventory'] ?? [],
                $parameters['exchange'] ?? []
            )
        );

        return new JsonResponse([
            'status' => 'ok'
        ]);
    }
}
