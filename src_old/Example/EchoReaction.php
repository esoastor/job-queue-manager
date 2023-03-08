<?php 
namespace EventManager\Example;

use EventManager\Event;

class EchoReaction implements Event\Reaction
{
    public function react(array $additionalData): void
    {
        echo $additionalData['message'];
    }
}