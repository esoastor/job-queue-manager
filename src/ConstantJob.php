<?php

namespace Esoastor\JobQueueManager;

abstract class ConstantJob extends Job
{
    protected bool $isConstant = true;

    # period in seconds, with minute step
    protected int $interval = 60;

    public function setInterval(int $interval): void
    {
        $this->interval = $interval;
    }

    public function getInterval(): int 
    {
        return $this->interval;
    }
}