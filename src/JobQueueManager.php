<?php

namespace Esoastor\JobQueueManager;

use Esoastor\JobQueueManager\Events\JobResult;
use \Database\Schema\Constructor;
use \Database\TableManager;
use \Esoastor\EventManager\ListenerProvider;
use \Esoastor\EventManager\EventDispatcher;

class JobQueueManager
{
    private TableManager $table;
    private EventDispatcher $dispatcher;

    public function __construct(private Constructor $constructor, private string $tableName)
    {        
        $provider = new ListenerProvider(['success', 'error']);
        $this->dispatcher = new EventDispatcher($provider);

        $this->initTable();
    }

    private function initTable(): void
    {
        $blueprint =  $this->constructor->getBlueprintBuilder();

        $this->constructor->createTable($this->tableName, [
            $blueprint->id(),
            $blueprint->text('job')->length(1000),
            $blueprint->varchar('status')->length(50),
            $blueprint->varchar('date_insert')->length(20),
            $blueprint->text('unique_job_id')->nullable(),
            $blueprint->varchar('next_run')->length(20)->nullable()
        ]);

        $this->table = $this->constructor->getDatabase()->table($this->tableName);
    }

    public function addListeners(string $name, array $listeners): void
    {
        $this->dispatcher ->addListeners($name, $listeners);
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

        $addJob = true;

        $fields = ['job' => serialize($job), 'status' => 'new', 'date_insert' => time()];

        if ($job->isConstant()) {
            $fields['next_run'] = time() + $job->getInterval();
        }

        if ($job->isUnique()) {
            $fields['unique_job_id'] = get_class($job);
            $addJob = !$this->isJobPresented($fields['unique_job_id']);
        }

        if ($addJob) {
            $this->table->insert($fields)->execute();
        }
    }

    public function isJobPresented(string $uniqueJobId): bool
    {
        $uniqueJobData = $this->table->select()->where('unique_job_id', $uniqueJobId)->execute();
        return !empty($uniqueJobData);
    }

    public function executeAll(bool $ignoreTimeOfNextRun = false): void
    {
        $jobsData = $this->table->select()->whereIn('status', ['new', 'on', 'error'])->execute();

        if (empty($jobsData)) {
            return;
        }

        foreach ($jobsData as $jobData) {
            $this->handleJob($jobData, $ignoreTimeOfNextRun);
        }
    }

    public function execute(string $jobId, bool $ignoreTimeOfNextRun = false): void
    {
        $jobData = $this->table->select()->where('id', $jobId)->execute()[0];

        $this->handleJob($jobData, $ignoreTimeOfNextRun);
    }

    protected function handleJob(array $jobData, $ignoreTimeOfNextRun = false): void
    {
        $job = unserialize($jobData['job']);
            
        if ($job->isConstant() && $jobData['next_run'] > time() && !$ignoreTimeOfNextRun) {
            return;
        }

        $this->table->update(['status' => 'running'])->where('id', (string) $jobData['id'])->execute();
    
        try {
            $job->handle();
        } catch (\Throwable $error) {
            $event = new JobResult(['job' => $this->serializeJob($job), 'error' => $error->getMessage()]);

            $this->dispatcher->dispatch('error', $event);

            $this->table->update(['status' => 'error'])->where('id', (string) $jobData['id'])->execute();
            return;
        }

        $event = new JobResult(['job' => $this->serializeJob($job)]);
        $this->dispatcher->dispatch('success', $event);

        if ($job->isConstant()) {
            $this->table->update(['status' => 'on', 'next_run' => time() + $job->getInterval()])->where('id', (string) $jobData['id'])->execute();
        } else {
            $this->table->delete()->where('id', (string) $jobData['id'])->execute();
        }
    }

    protected function serializeJob(Job $job): string
    {
        try {
            $jobText = serialize($job);
        } catch (\Throwable $serializeProblem) {
            # i know and i dont care
            $jobText = '[' . get_class($job) . '][' . $serializeProblem->getMessage() . ']';
        }

        return $jobText;
    }
}
