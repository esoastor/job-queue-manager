<?php

namespace Esoastor\JobQueueManager;

use \Database\Schema\Constructor;
use \Database\TableManager;
use Throwable;

class JobQueueManager
{
    private TableManager $table;

    public function __construct(private Constructor $constructor, private string $tableName)
    {
    }

    public function initTable(): void
    {
        $blueprint =  $this->constructor->getBlueprintBuilder();

        $this->constructor->createTable($this->tableName, [
            $blueprint->id(),
            $blueprint->varchar('job')->length(50),
            $blueprint->text('status')->length(1000),
            $blueprint->varchar('date_insert')->length(20),
        ]);

        $this->table = $this->constructor->getDatabase()->table($this->tableName);
    }

    public function addJob(Job $job): void
    {
        if (!isset($this->table)) {
            throw new Errors\TableNotFound($this->tableName);
        }

        $this->table->insert(['job' => serialize($job), 'status' => 'new', 'date_insert' => time()])->execute();
    }

    public function executeJob(): void
    {
        $lastJobData = $this->table->select()->where('status', 'new')->execute()[0];

        $job = unserialize($lastJobData['job']);

        $this->table->update(['status' => 'pending'])->where('id', (string) $lastJobData['id'])->execute();

        try {
            $job->handle();
        } catch (\Throwable $error) {
            $this->table->update(['status' => 'error'])->where('id', (string) $lastJobData['id'])->execute();
            die;
        }

        $this->table->delete()->where('id', (string) $lastJobData['id'])->execute();
    }
}
