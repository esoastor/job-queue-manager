There are examle in src/Example. In action it looks something like this

```
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
```
You must create TriggerWatcher, Reaction and Event classes. Load Watcher and Reaction in Event object, load Event in EventWatcher object
You can add additional TriggerWatchers and Reactions to Event object by ```addTriggerWatcher``` and ```addReaction``` methods.
When checked if Event is triggered, check method will go through all trigger watchers and if all of them are triggered - event considered triggered