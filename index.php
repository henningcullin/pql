<?php

require_once('pql.php');

$dir = __DIR__ . '/db/';
$file = 'Database.db';

if (!file_exists($dir)) {
    mkdir($dir, 0775, true);
}

class Task extends FromRow {
    public int $id;
    public string $title;
    public string $description;
    public Machine $machine;
}


class Machine extends FromRow {
    public int $id;
    public string $name;
    public string $make;
}

$conn = new SQLite3($dir . $file);

$result = pql::query_as(
    Task::class,
    'SELECT task.id, task.title, task.description, machine.id, machine.name, machine.make FROM 
        task
    LEFT JOIN 
        machine ON machine.id = task.machine
    WHERE machine.id = ?;',
    1
)->fetch_all($conn);

echo '<pre>';
var_dump($result);