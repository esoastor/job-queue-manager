<?php
require_once 'vendor/autoload.php';
require_once 'example/PutInFileJob.php';
require_once 'example/PutInFileConstantJob.php';


use Esoastor\JobQueueManager\JobQueueManager;
use Database\Schema\Mysql\MysqlConstructor;

$constructor = new MysqlConstructor('mysql:3306', 'test', 'test', 'test');

$manager = new JobQueueManager($constructor, 'test_table');
$manager->initTable();
$oneTimeJob = new PutInFileJob();
$constantJob = new PutInFileConstantJob();
$constantJob->setInterval(20);

$manager->addJob($oneTimeJob);
$manager->addJob($constantJob);

$manager->executeAll();