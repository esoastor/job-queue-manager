<?php
require_once 'vendor/autoload.php';

use EventManager\EventWatcher;
use EventManager\Example;

$triggerWatcher = new Example\RandomBoolWatcher();
$reaction = new Example\EchoReaction();

$event1 = new Example\RandomBoolEvent($triggerWatcher, $reaction);
$event2 = new Example\RandomBoolEvent($triggerWatcher, $reaction);


$eventsWatcher = new EventWatcher();

$eventsWatcher->addEvent($event1);
$eventsWatcher->addEvent($event2);

$eventsWatcher->check();
$eventsWatcher->react();