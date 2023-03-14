<?php

namespace Esoastor\JobQueueManager;

abstract class Job
{
    protected bool $isConstant;

    public function isConstant(): bool
    {
        return $this->isConstant;
    }

    abstract public function handle(): void;
}