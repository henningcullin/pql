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

/* public function __construct($row)
{
    $this->id = $row['machine'];
    $this->name = $row['name'];
    $this->make = $row['make'];
} */

$conn = new SQLite3($dir . $file);

/* $result = $conn->query('CREATE TABLE IF NOT EXISTS machine (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255),
    make VARCHAR(255)
);');

$result = $conn->query('CREATE TABLE IF NOT EXISTS task (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255),
    description TEXT,
    machine INTEGER
);'); */

/* $result = $conn->query('INSERT INTO machine (name, make) VALUES("V60", "Volvo")');
$result = $conn->query('INSERT INTO machine (name, make) VALUES("V90", "Volvo")');
$result = $conn->query('INSERT INTO machine (name, make) VALUES("Corolla", "Toyota")');
$result = $conn->query('INSERT INTO machine (name, make) VALUES("Escort", "Ford")');
$result = $conn->query('INSERT INTO machine (name, make) VALUES("Golf", "Volkswagen")');
$result = $conn->query('INSERT INTO machine (name, make) VALUES("Passat", "Volkswagen")'); */

/* $result = $conn->query('INSERT INTO task (title, description, machine) VALUES("Very Urget", "Replace Engine Oil", 4)');
$result = $conn->query('INSERT INTO task (title, description, machine) VALUES("Quite Urget", "Fix Aux Cable", 2)');
$result = $conn->query('INSERT INTO task (title, description, machine) VALUES("Not urgent", "Refill blinker fluid", 1)'); */

/* $result = pql::query(
    'SELECT * FROM task LEFT JOIN machine ON task.machine = machine.id'
)->fetch_all($conn); */

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