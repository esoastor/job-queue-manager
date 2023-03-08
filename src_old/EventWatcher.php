<?php

namespace Esoastor\EventManager;

use EventManager\Event\Event;
use \Database\Schema\Constructor;

class EventWatcher
{
    private array $events = [];
    private string $tableName = 'triggered_events';

    public function __construct(private Constructor $constructor)
    {
        $blueprint =  $this->constructor->getBlueprintBuilder();

        $this->constructor->createTable($this->tableName, [
            $blueprint->id(),
            $blueprint->string('event_class')->length(50),
            $blueprint->string('params')->length(1000),
        ]);

        $this->table = $this->constructor->getDatabase()->table($this->tableName);

    }

    public function addEvent(Event $event): void
    {
        $this->events[] = $event;
    }

    public function check(): void
    {
        foreach ($this->events as $eventId => $event) {
            if ($event->isTriggered()) {
                $this->addEventToQueye($eventId);
            }
        }
    }

    public function react(): void
    {
        $query = "SELECT * FROM {$this->tableName}";
        $statement = $this->pdo->query($query);

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $row) {
            $this->events[$row['event_id']]->react();
            $this->removeEventFromQueye($row['id'], $row['event_id']);
        }
    }

    private function addEventToQueye(string $eventId): void
    {
        $query = "INSERT INTO {$this->tableName} (event_id) VALUES ({$eventId})";
        $this->pdo->exec($query);
    }

    private function removeEventFromQueye(string $queryId, string $eventId): void
    {
        $query = "DELETE FROM {$this->tableName} WHERE id = {$queryId} AND event_id = {$eventId}";
        $this->pdo->exec($query);
    }
}