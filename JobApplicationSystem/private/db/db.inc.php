<?php

// Definerer databasen  
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'is115DB');
$dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;


// Oppretter en PDO-forbindelse

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

// bruker MySQL
//Vår DB heter is115DB
$server = "localhost";
$brukernavn = "root";
$passord = "";
$database = "is115DB";


// MySQL tilkobling
$conn = new mysqli($server, $brukernavn, $passord, $database);

if ($conn->connect_error) {
    die("Tilkobling mislyktes: " . $conn->connect_error);
}

