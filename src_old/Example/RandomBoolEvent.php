<?php 
namespace EventManager\Example;

use EventManager\Event;

class RandomBoolEvent extends Event\Event
{
    private string $message;

    public function isTriggered(): bool
    {
        $isTriggered = parent::isTriggered();

        if ($isTriggered) {
            $this->message = 'number of the day is: ' . rand(0, 1000) . '</br>';
        }

        return $isTriggered;
    }

    public function react(array $additionalData = []): void
    {
        parent::react(['message' => $this->message]);
    }
}