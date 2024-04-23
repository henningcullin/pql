<?php

require_once('pql.php');

$dir = __DIR__ . '/db/';
$file = 'Database.db';

if (!file_exists($dir)) {
    mkdir($dir, 0775, true);
}

class Machine {

}

$conn = new SQLite3($dir . $file);

$first_id = 5;
$second_id = 66;

pql::query_as(
    'Machine', 
    'SELECT * FROM machine WHERE id = ? OR id <> ?', 
    $first_id, 
    $second_id
)->fetch_one($conn);