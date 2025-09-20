<?php

namespace App\SharedKernel\Infrastructure\Messenger\Bus;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Exception\LogicException;

class QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @param object|Envelope $query
     *
     * @return mixed The handler returned value
     */
    public function dispatch($query)
    {
        return $this->handle($query);
    }

    /**
     * @param Envelope $envelope
     * @return mixed The handler returned value
     */
    public static function getResult(Envelope $envelope)
    {
        $handledStamps = $envelope->all(HandledStamp::class);

        if (!$handledStamps) {
            throw new LogicException(sprintf('Message of type "%s" was handled zero times. Exactly one handler is expected".', \get_class($envelope->getMessage())));
        }

        if (count($handledStamps) > 1) {
            $handlers = implode(', ', array_map(function (HandledStamp $stamp): string {
                return sprintf('"%s"', $stamp->getHandlerAlias() ?? $stamp->getCallableName());
            }, $handledStamps));

            throw new LogicException(sprintf('Message of type "%s" was handled multiple times. Only one handler is expected when using "%s::%s()", got %d: %s.', \get_class($envelope->getMessage()), '', __FUNCTION__, \count($handledStamps), $handlers));
        }

        return $handledStamps[0]->getResult();
    }
}
