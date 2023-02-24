<?php

namespace EventManager\Event;

class Event 
{
    public function __construct(protected TriggerWatcher $triggerWatcher, protected Reaction $reaction)
    {   
    }

    public function check(): void
    {

    }

    public function react(): void
    {
        
    }
}
