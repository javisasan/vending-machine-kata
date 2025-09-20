<?php

namespace App\SharedKernel\Infrastructure\Messenger\Event\Provider;

class EventProvider implements ProvidesEvents
{
    use EventProviderCapabilitiesTrait;

    /** @var EventProvider */
    private static $instance = null;

    public static function instance()
    {
        if (null == static::$instance) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    /**
     * EventProvider constructor.
     */
    public function __construct()
    {
        $this->events = [];
    }
}
