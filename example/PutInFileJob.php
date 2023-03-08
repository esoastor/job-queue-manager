<?php

use Esoastor\JobQueueManager\Job;

class PutInFileJob implements Job
{
    public function handle(): void
    {
        file_put_contents('./hello.txt', time());
    }
}