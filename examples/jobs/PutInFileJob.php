<?php

use Esoastor\JobQueueManager\OneTimeJob;

class PutInFileJob extends OneTimeJob
{
    public function handle(): void
    {
        file_put_contents('./job-output.txt', 'PutInFileJob: ' . date('H:i:s') . PHP_EOL, FILE_APPEND);
    }
}