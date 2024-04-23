<?php

require_once('pql.php');

$dir = __DIR__ . '/db/';
$file = 'Database.db';

if (!file_exists($dir)) {
    mkdir($dir, 0775, true);
}

class Machine {
    public int $id;
    public string $name;
    public string $make;

    public function __construct($row)
    {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->make = $row['make'];
    }
}

$conn = new SQLite3($dir . $file);

$first_id = 5;
$second_id = 66;

$result = $conn->query('CREATE TABLE IF NOT EXISTS machine (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255),
    make VARCHAR(255)
);');

/* $result = $conn->query('INSERT INTO machine (name, make) VALUES("Corolla", "Toyota")'); */

$result = pql::query(
    'SELECT * FROM machine'
)->fetch_all($conn);

/* pql::query_as(
    'Machine', 
    'SELECT * FROM machine WHERE id = ? OR id <> ?', 
    $first_id, 
    $second_id
)->fetch_one($conn); */