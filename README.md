## job-queue-manager ##

```
require_once 'vendor/autoload.php';
require_once 'example/PutInFileJob.php';


use Esoastor\JobQueueManager\JobQueueManager;
use Database\Schema\Mysql\MysqlConstructor;

$constructor = new MysqlConstructor('mysql:3306', 'test', 'test', 'test');

$manager = new JobQueueManager($constructor, 'test_table');
$manager->initTable();
$job = new PutInFileJob();

$manager->addJob($job);

# all jobs will be executed
$manager->executeJobs();
```

work statuses
- new - new constant or regular job
- running - job is running now
- on - inactive constant job
- error - something wrong

### events and listeners ###
add listeners with addListeners method

available 'error' and 'success' events
