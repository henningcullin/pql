<?php

const EXECUTE_QUERY = false;

$servername = "localhost";
$username = "root";
$password = "";
$database = "pql";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// OWNERS
$owners_sql = "
    INSERT INTO `owners` (`name`, `email`) VALUES ('Henning', 'henning@email.com');
    INSERT INTO `owners` (`name`, `email`) VALUES ('Oliver', 'oliver@email.com');
";

// CAR_MAKE
$car_make_sql = "
    INSERT INTO `car_make` (`name`, `email`) VALUES ('Saab', 'saab@email.com');
    INSERT INTO `car_make` (`name`, `email`) VALUES ('Volvo', 'volvo@email.com');
";

// CARS
$cars_sql = "
    INSERT INTO `cars` (`make`, `model`, `owner`, `year`) VALUES ((SELECT id FROM `car_make` WHERE name = 'Saab'), '9-5', (SELECT id FROM `owners` WHERE name = 'Henning'), 2005);
    INSERT INTO `cars` (`make`, `model`, `owner`, `year`) VALUES ((SELECT id FROM `car_make` WHERE name = 'Volvo'), '850', (SELECT id FROM `owners` WHERE name = 'Oliver'), 1994);
";

// PART_BRANDS
$part_brands_sql = "
    INSERT INTO `part_brands` (`name`, `email`) VALUES ('Saab Spare Parts Co.', 'info@saabsparepartsco.com');
    INSERT INTO `part_brands` (`name`, `email`) VALUES ('Volvo Auto Parts Ltd.', 'contact@volvoautopartsltd.com');
    INSERT INTO `part_brands` (`name`, `email`) VALUES ('Saab Auto Components', 'support@saabautocomponents.com');
    INSERT INTO `part_brands` (`name`, `email`) VALUES ('Volvo Car Parts Corp.', 'help@volvocarpartscorp.com');
";

// PARTS
$parts_sql = "
    INSERT INTO `parts` (`name`, `brand`) VALUES ('Saab Engine', (SELECT id FROM part_brands WHERE name = 'Saab Spare Parts Co.'));
    INSERT INTO `parts` (`name`, `brand`) VALUES ('Saab Transmission', (SELECT id FROM part_brands WHERE name = 'Saab Spare Parts Co.'));
    INSERT INTO `parts` (`name`, `brand`) VALUES ('Saab Brake Pads', (SELECT id FROM part_brands WHERE name = 'Saab Auto Components'));
    INSERT INTO `parts` (`name`, `brand`) VALUES ('Saab Oil Filter', (SELECT id FROM part_brands WHERE name = 'Saab Auto Components'));
    
    INSERT INTO `parts` (`name`, `brand`) VALUES ('Volvo Engine', (SELECT id FROM part_brands WHERE name = 'Volvo Auto Parts Ltd.'));
    INSERT INTO `parts` (`name`, `brand`) VALUES ('Volvo Transmission', (SELECT id FROM part_brands WHERE name = 'Volvo Auto Parts Ltd.'));
    INSERT INTO `parts` (`name`, `brand`) VALUES ('Volvo Brake Pads', (SELECT id FROM part_brands WHERE name = 'Volvo Car Parts Corp.'));    
";

// CAR_PARTS
$car_parts_sql = "
    INSERT INTO `car_parts` (`car_id`, `part_id`) VALUES ((SELECT id FROM cars WHERE model = '9-5'), (SELECT id FROM parts WHERE name = 'Saab Engine'));
    INSERT INTO `car_parts` (`car_id`, `part_id`) VALUES ((SELECT id FROM cars WHERE model = '9-5'), (SELECT id FROM parts WHERE name = 'Saab Transmission'));
    INSERT INTO `car_parts` (`car_id`, `part_id`) VALUES ((SELECT id FROM cars WHERE model = '9-5'), (SELECT id FROM parts WHERE name = 'Saab Brake Pads'));
    INSERT INTO `car_parts` (`car_id`, `part_id`) VALUES ((SELECT id FROM cars WHERE model = '9-5'), (SELECT id FROM parts WHERE name = 'Saab Oil Filter'));
    
    INSERT INTO `car_parts` (`car_id`, `part_id`) VALUES ((SELECT id FROM cars WHERE model = '850'), (SELECT id FROM parts WHERE name = 'Volvo Engine'));
    INSERT INTO `car_parts` (`car_id`, `part_id`) VALUES ((SELECT id FROM cars WHERE model = '850'), (SELECT id FROM parts WHERE name = 'Volvo Transmission'));
    INSERT INTO `car_parts` (`car_id`, `part_id`) VALUES ((SELECT id FROM cars WHERE model = '850'), (SELECT id FROM parts WHERE name = 'Volvo Brake Pads'));    
";

// Concatenate all SQL queries
$all_sql = $owners_sql . $car_make_sql . $cars_sql . $part_brands_sql . $parts_sql .  $car_parts_sql;

if (!EXECUTE_QUERY) {
    echo "<br> Did not execute queries, change EXECUTE_QUERY to true to execute them";
    return;
}


// Execute multi-query
if ($conn->multi_query($all_sql)) {
    echo "Multi-query executed successfully.";
} else {
    echo "Error executing multi-query: " . $conn->error;
}

// Close connection
$conn->close();
