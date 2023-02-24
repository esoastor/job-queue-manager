<?php

namespace EventManager;

use EventManager\Event\Event;

class EventWatcher
{
    private array $events = [];

    public function addEvent(Event $event): void
    {
        $this->events[] = $event;
    }
}