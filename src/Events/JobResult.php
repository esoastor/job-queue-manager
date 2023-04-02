<?php

namespace Esoastor\JobQueueManager\Events;

class JobResult
{
    public function __construct(public array $info)
    {
    }
}
