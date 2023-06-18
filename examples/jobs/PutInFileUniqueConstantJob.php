<?php

use Esoastor\JobQueueManager\ConstantJob;

class PutInFileUniqueConstantJob extends ConstantJob
{
    protected bool $isUnique = true;

    public function handle(): void
    {
        file_put_contents('./job-output.txt', 'PutInFileUniqueConstantJob: ' . date('H:i:s') . PHP_EOL, FILE_APPEND);
    }
}