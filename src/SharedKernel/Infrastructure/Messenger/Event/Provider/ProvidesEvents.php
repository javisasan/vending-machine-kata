<?php

namespace App\SharedKernel\Infrastructure\Messenger\Event\Provider;

use App\SharedKernel\Domain\Event\DomainEvent;

interface ProvidesEvents
{
    /**
     * @return DomainEvent[]
     */
    public function releaseEvents();
}
