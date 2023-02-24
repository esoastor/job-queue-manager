<?php

namespace EventManager\Event;

interface TriggerWatcher 
{
    public function isTriggered(): bool;
}
