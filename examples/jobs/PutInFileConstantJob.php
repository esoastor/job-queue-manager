<?php

use Esoastor\JobQueueManager\ConstantJob;

class PutInFileConstantJob extends ConstantJob
{
    public function handle(): void
    {
        file_put_contents('./job-output.txt', 'PutInFileConstantJob: ' . date('H:i:s') . PHP_EOL, FILE_APPEND);
    }
}