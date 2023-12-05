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

// Hent tittel fra jobbannonser
$sql_select_tittel = "SELECT tittel FROM jobbannonser WHERE id = ?";
$stmt_select_tittel = $conn->prepare($sql_select_tittel);
$stmt_select_tittel->bind_param("i", $jobbannonse_id);
$stmt_select_tittel->execute();
$result_tittel = $stmt_select_tittel->get_result();

if ($result_tittel->num_rows > 0) {
    $row_tittel = $result_tittel->fetch_assoc();
    $jobbannonse_tittel = $row_tittel['tittel'];

    echo "<h1>Søkere på stillingen som  $jobbannonse_tittel </h1>";

    // Hent søknader med phoneNumber og email fra brukeren
    $sql_select_soknader = "SELECT soknader.*, users.username, users.phoneNumber, users.email FROM soknader 
                            JOIN users ON soknader.jobbsoker_id = users.id 
                            WHERE soknader.jobbannonse_id = ?";
    $stmt_select_soknader = $conn->prepare($sql_select_soknader);
    $stmt_select_soknader->bind_param("i", $jobbannonse_id);
    $stmt_select_soknader->execute();
    $result_soknader = $stmt_select_soknader->get_result();

    if ($result_soknader->num_rows > 0) {
        echo "<table style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background-color: #f2f2f2;'><th>Bruker</th><th>Søknadstekst</th><th>Søknadsdato</th><th>Tlf</th><th>Email</th><th>Søknad</th></tr>";

        while ($row = $result_soknader->fetch_assoc()) {
            echo "<tr>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $row["username"] . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $row["soknadstekst"] . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $row["soknadsdato"] . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $row["phoneNumber"] . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $row["email"] . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'><a href='" . $row["pdf_path"] . "' target='_blank'>Last ned PDF</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Ingen søknader funnet.";
    }

    $stmt_select_soknader->close();
} else {
    echo "Feil: Fant ikke tittel for jobbannonse.";
}

$stmt_select_tittel->close();

include 'inc/footer.php';
?>
