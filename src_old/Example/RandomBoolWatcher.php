<?php 
namespace EventManager\Example;

use EventManager\Event;

class RandomBoolWatcher implements Event\TriggerWatcher
{
    public function isTriggered(): bool
    {
        $isTriggered = (bool) rand(0, 1);
        return $isTriggered;
    }
}