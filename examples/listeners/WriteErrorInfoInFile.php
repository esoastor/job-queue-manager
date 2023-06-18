<?php

class WriteErrorInfoInFile implements \Esoastor\EventManager\Listener
{
    public function handle(object $event): void
    {
        file_put_contents('./event-trigger.txt', 'Error occured on: ' . date('H:i:s') . PHP_EOL, FILE_APPEND);
    }
}