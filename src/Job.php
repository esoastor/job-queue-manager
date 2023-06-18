<?php

namespace Esoastor\JobQueueManager;

abstract class Job
{
    protected bool $isConstant;
    protected bool $isUnique = false;

    public function isConstant(): bool
    {
        return $this->isConstant;
    }

    public function isUnique(): bool
    {
        return $this->isUnique;
    }

    abstract public function handle(): void;
}