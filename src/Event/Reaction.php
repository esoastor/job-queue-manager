<?php

namespace EventManager\Event;

interface Reaction 
{
    public function react(array $additionalData): void;
}
