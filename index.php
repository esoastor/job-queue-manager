<?php
require_once 'vendor/autoload.php';
require_once 'examples/jobs/PutInFileJob.php';
require_once 'examples/jobs/PutInFileConstantJob.php';
require_once 'examples/jobs/PutInFileUniqueConstantJob.php';
require_once 'examples/jobs/ErrorJob.php';
require_once 'examples/listeners/WriteErrorInfoInFile.php';


use Esoastor\JobQueueManager\JobQueueManager;
use Database\Schema\Mysql\MysqlConstructor;

$constructor = new MysqlConstructor('mysql:3306', 'test', 'test', 'test');

$manager = new JobQueueManager($constructor, 'test_table');

$oneTimeJob = new PutInFileJob();

$constantJob = new PutInFileConstantJob();
$constantJob->setInterval(20);

$errorJob = new ErrorJob();

$uniqueConstantJob = new PutInFileUniqueConstantJob();

$manager->addListeners('error', [
    WriteErrorInfoInFile::class
]);

$manager->addJob($oneTimeJob);
$manager->addJob($constantJob);
$manager->addJob($errorJob);
$manager->addJob($uniqueConstantJob);

$manager->executeAll();