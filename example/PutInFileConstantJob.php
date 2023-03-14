<?php

use Esoastor\JobQueueManager\ConstantJob;

class PutInFileConstantJob extends ConstantJob
{
    public function handle(): void
    {
        file_put_contents('./hello_constant.txt', (string) time() . 'SDGASDG');
    }
}