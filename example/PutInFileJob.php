<?php

use Esoastor\JobQueueManager\OneTimeJob;

class PutInFileJob extends OneTimeJob
{
    public function handle(): void
    {
        file_put_contents('./hello.txt', time());
    }
}