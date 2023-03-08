<?php

namespace EventManager\Event;

abstract class Event 
{
    private array $triggerWatchers = [];
    private array $reactions = [];

    public function __construct(TriggerWatcher $triggerWatcher, Reaction $reaction)
    {
        $this->triggerWatchers[] = $triggerWatcher;
        $this->reactions[] = $reaction;
    }

    public function addTriggerWatcher(TriggerWatcher $triggerWatcher): self
    {
        $this->triggerWatchers[] = $triggerWatcher;
        return $this;
    }

    public function addReaction(Reaction $reaction)
    {
        $this->reactions[] = $reaction;
        return $this;
    }

    public function isTriggered(): bool
    {
        $isTriggered = true;
        
        foreach ($this->triggerWatchers as $watcher) {
            $isTriggered = $isTriggered && $watcher->isTriggered();
        }
 
        return $isTriggered;
    }

    public function react(array $additionalData = []): void
    {
        foreach ($this->reactions as $reaction) {
            $reaction->react($additionalData);
        }
    }
}
