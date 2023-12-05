<?php

// Using PDO
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'is115DB');
$dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

// Using MySQLi
$server = "localhost";
$brukernavn = "root";
$passord = "";
$database = "is115DB";

$conn = new mysqli($server, $brukernavn, $passord, $database);

if ($conn->connect_error) {
    die("Tilkobling mislyktes: " . $conn->connect_error);
}

// Now you can use $pdo for PDO operations and $conn for MySQLi operations in the same file.