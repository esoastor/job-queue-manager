<?php

use Esoastor\JobQueueManager\OneTimeJob;

class ErrorJob extends OneTimeJob
{
    public function handle(): void
    {
        throw new Error('123');
    }
}