<?php
require_once 'vendor/autoload.php';
require_once 'example/PutInFileJob.php';


use Esoastor\JobQueueManager\JobQueueManager;
use Database\Schema\Mysql\MysqlConstructor;

$constructor = new MysqlConstructor('mysql:3306', 'test', 'test', 'test');

$manager = new JobQueueManager($constructor, 'test_table');
$manager->initTable();
$job = new PutInFileJob();

$manager->addJob($job);

$manager->executeJob();