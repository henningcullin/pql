<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "pql";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


class PartBrand
{
    public int $id;
    public string $name;
    public string $email;
}

class Part
{
    public int $id;
    public string $name;
    public PartBrand $brand;
}

class CarMake
{
    public int $id;
    public string $name;
    public string $email;
}

class Car
{
    public int $id;
    public CarMake $make;
    public string $model;
    public array $parts;
    public int $year;
}

class Owner
{
    public int $id;
    public string $name;
    public string $email;
    public array $cars;
}



$result = $conn->query("SELECT * FROM parts");

$arr = $result->fetch_all(MYSQLI_ASSOC);

foreach ($arr as &$part) {
    echo '<br>';
    var_dump($part);
}
