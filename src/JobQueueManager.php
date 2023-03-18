<?php

namespace Esoastor\JobQueueManager;

use \Database\Schema\Constructor;
use \Database\TableManager;


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
            $blueprint->text('job')->length(1000),
            $blueprint->varchar('status')->length(50),
            $blueprint->varchar('date_insert')->length(20),
            $blueprint->varchar('next_run')->length(20)->nullable()
        ]);

        $this->table = $this->constructor->getDatabase()->table($this->tableName);
    }

    public function getJobList(): array
    {
        return $this->table->select()->execute();
    }

    public function addJob(Job $job): void
    {
        if (!isset($this->table)) {
            throw new Errors\TableNotFound($this->tableName);
        }

        $fields = ['job' => serialize($job), 'status' => 'new', 'date_insert' => time()];

        if ($job->isConstant()) {
            $fields['next_run'] = time() + $job->getInterval();
        }

        $this->table->insert($fields)->execute();
    }

    public function executeAll(bool $ignoreTimeOfNextRun = false): void
    {
        $jobsData = $this->table->select()->whereIn('status', ['new', 'wait'])->execute();

        if (empty($jobsData)) {
            return;
        }
        

        foreach ($jobsData as $jobData) {
            $this->handleJob($jobData);
        }
    }

    public function execute(string $jobId, bool $ignoreTimeOfNextRun = false): void
    {
        $jobData = $this->table->select()->where('id', $jobId)->execute()[0];

        $this->handleJob($jobData, $ignoreTimeOfNextRun);
    }

    protected function handleJob(array $jobData, $ignoreTimeOfNextRun = false)
    {
        $job = unserialize($jobData['job']);
            
        if ($job->isConstant() && $jobData['next_run'] > time() && !$ignoreTimeOfNextRun) {
            continue;
        }

        $this->table->update(['status' => 'pending'])->where('id', (string) $jobData['id'])->execute();
    
        try {
            $job->handle();
        } catch (\Throwable $error) {
            $this->table->update(['status' => 'error'])->where('id', (string) $jobData['id'])->execute();
            die;
        }

        if ($job->isConstant()) {
            $this->table->update(['status' => 'wait', 'next_run' => time() + $job->getInterval()])->where('id', (string) $jobData['id'])->execute();
        } else {
            $this->table->delete()->where('id', (string) $jobData['id'])->execute();
        }
    }
}
