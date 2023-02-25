<?php

namespace EventManager;

use EventManager\Event\Event;

class EventWatcher
{
    private array $events = [];

    # TODO: fix it later
    private string $dbFileName = 'test.sqlite';
    private string $tableName = 'triggered_events';
    private \PDO $pdo;

    public function __construct()
    {
        # TODO: fix it later
        $this->pdo = new \PDO('sqlite:' . $this->dbFileName);

        $query = <<<TEXT
            CREATE TABLE IF NOT EXISTS {$this->tableName} (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                event_id INTEGER NOT NULL
            );
        TEXT;

        $this->pdo->exec($query);

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