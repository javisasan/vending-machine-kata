<?php

namespace App\SharedKernel\Infrastructure\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Throwable;

class FailureCatcherMiddleware implements MiddlewareInterface
{
    /**
     * @param Envelope $envelope
     * @param StackInterface $stack
     * @return Envelope
     * @throws \Throwable
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        /*
        try {
            $returnedEnvelope = $stack->next()->handle($envelope, $stack);
        } catch (HandlerFailedException $e) {
            throw $e->getNestedExceptions()[0];
        }
         */

        try {
            $returnedEnvelope = $stack->next()->handle($envelope, $stack);
        } catch (Throwable $e) {
            throw $e->getPrevious();
        }

        return $returnedEnvelope;
    }
}
