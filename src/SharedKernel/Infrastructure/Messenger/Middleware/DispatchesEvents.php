<?php

namespace App\SharedKernel\Infrastructure\Messenger\Middleware;

use App\SharedKernel\Infrastructure\Messenger\Event\Provider\EventProvider;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class DispatchesEvents implements MiddlewareInterface
{
    /** @var MessageBusInterface  */
    private $eventBus;

    public function __construct(MessageBusInterface $messengerBusEvents)
    {
        $this->eventBus = $messengerBusEvents;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);

        foreach (EventProvider::instance()->releaseEvents() as $event) {
            $this->eventBus->dispatch($event);
        }

        return $envelope;
    }
}
