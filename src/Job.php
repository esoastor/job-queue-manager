<?php

namespace Esoastor\JobQueueManager;

interface Job
{
    public function handle(): void;
}