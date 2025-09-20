<?php

namespace App\SharedKernel\Infrastructure\Messenger\Event\Provider;

use App\SharedKernel\Domain\Event\DomainEvent;

trait EventProviderCapabilitiesTrait
{
    private $events = array();

    public function releaseEvents()
    {
        $events = $this->events;
        $this->events = array();
        return $events;
    }

    public function raise(DomainEvent $event)
    {
        $this->events[] = $event;
    }
}
