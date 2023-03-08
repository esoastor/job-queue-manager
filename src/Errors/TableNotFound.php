<?php

namespace Esoastor\JobQueueManager\Errors;

class TableNotFound extends \Exception
{
    public function __construct(string $tableName)
    {
        $this->message = "Table [{$tableName}] does not exists, init it with JobManager initTable method\n";
    }
}

