<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION['username']) || $_SESSION['userType'] !== 'arbeidsgiver') {
    header("Location: login.php");
    exit();
}

include 'inc/header.php';

$server = "localhost";
$brukernavn = "root";
$passord = "";
$database = "is115DB";

$conn = new mysqli($server, $brukernavn, $passord, $database);

if ($conn->connect_error) {
    die("Tilkobling mislyktes: " . $conn->connect_error);
}

// Get jobbannonse_id from the URL parameter
$jobbannonse_id = isset($_GET['jobbannonse_id']) ? $_GET['jobbannonse_id'] : 0;

$sql_select_soknader = "SELECT * FROM soknader WHERE jobbannonse_id = ?";
$stmt_select_soknader = $conn->prepare($sql_select_soknader);
$stmt_select_soknader->bind_param("i", $jobbannonse_id);
$stmt_select_soknader->execute();
$result_soknader = $stmt_select_soknader->get_result();

if ($result_soknader->num_rows > 0) {
    while ($row = $result_soknader->fetch_assoc()) {
        echo "Soknad ID: " . $row["soknad_id"] . " - Jobbannonse ID: " . $row["jobbannonse_id"] . " - Jobbsoker ID: " . $row["jobbsoker_id"];
        echo " - Soknadstekst: " . $row["soknadstekst"] . " - Soknadsdato: " . $row["soknadsdato"] . " - PDF Path: " . $row["pdf_path"] . "<br>";
    }
} else {
    echo "Ingen søknader funnet.";
}

$stmt_select_soknader->close();

include 'inc/footer.php';
?>
