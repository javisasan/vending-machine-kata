<?php

namespace App\Vending\Infrastructure\UI\Controller;

use App\Vending\Application\Machine\Command\CreditCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;

class CreditController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {
    }

    public function __invoke(string $value): JsonResponse
    {
        $this->messageBus->dispatch(
            new CreditCommand((float) $value)
        );

        return new JsonResponse([
            'status' => 'ok'
        ]);
    }
}
