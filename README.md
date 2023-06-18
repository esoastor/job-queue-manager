# job-queue-manager #

Allows creating and managing a job queue.

## start ##

The `JobQueueManager` object is used to initialize the system. The constructor takes an object `\Database\Schema\Constructor` (`esoastor/database-manager`) and the table name. The object will create the table in the database and be ready to work.

## Jobs creation and configuration ##

Jobs are created based on the abstract classes `Esoastor\JobQueueManager\ConstantJob` and `Esoastor\JobQueueManager\OneTimeJob`.

In these classes, you need to implement the `handle()` method, which contains the main logic of the job.

The following methods are available in each job by default:

- `isUnique(): bool` - checks if the job is unique. Only one instance of a unique job can exist.
- `isConstant(): bool` - checks if the job is constant. A constant job will be executed at a specified interval.

### OneTimeJob ###

A job inherited from this class will be executed once and then removed.

### ConstantJob ###

Constant job will be executed at a specified interval. The default interval is **1 minute**. Additional methods are available:

- `getInterval(): int` - retrieves the current interval (in seconds).
- `setInterval(int $interval)` - sets the interval (in seconds).

### unique job ###

Any job can be made unique by defining the `protected bool $isUnique` parameter in the class with a value of `true`.

### job statuses ###

Job statuses that can be viewed in the database:

- **new**
- **running** - job is running now
- **on** - inactive constant job that will be run when the time is right
- **error** - something wrong

## Adding jobs to queue ##

`JobQueueManager` is the class for adding and executing jobs. Management is done using the following methods:

- `addJob($job)` - adds a job. Takes an object `Esoastor\JobQueueManager\Job`.
- `execute(string $jobId, bool $ignoreTimeOfNextRun = false)` - executes a job.
- `executeAll(bool $ignoreTimeOfNextRun = false)` - executes all pending jobs.

If you pass `true` for the argument `$ignoreTimeOfNextRun` in the last two functions, the time constraints of constant jobs will be ignored, and they will be executed out of order.

### events and listeners ###

Listeners can be added using `addListeners()`.
Listeners are subclasses of `\Esoastor\EventManager\Listener` (`esoastor/event-manager`). To work, you need to implement the `handle(object $event)` method.

Example of adding listeners:

```php
$jobQueueManager->addListeners('error', [
    NotifyAboutErrorByEmail::class,
]);
```

Available events are **error** and **success**.

## examples & development ##

Job and Listener examples can be found in /examples, and the initialization example is in index.php.

Development server - http://localhost:8899/, launched at the start of docker-compose.yml.