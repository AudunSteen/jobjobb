<?php
session_start();

// Sjekker om brukeren er logget inn som arbeidsgiver og har riktige rettigheter
if (!isset($_SESSION['username']) || $_SESSION['userType'] !== 'arbeidsgiver') {
    header("Location: login.php");
    exit();
}

include 'inc/header.php'; // Inkluderer navigasjonsmeny og rettigheter
include 'inc/db.inc.php'; // Inkluderer database-tilkobling


$conn = new mysqli($server, $brukernavn, $passord, $database);

// Sjekker etter tilkoblingsfeil
if ($conn->connect_error) {
    die("Tilkobling mislyktes: " . $conn->connect_error);
}

// Henter bruker-ID for den innloggede arbeidsgiveren
$arbeidsgiver_username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $arbeidsgiver_username);
$stmt->execute();
$result = $stmt->get_result();

// Sjekker om brukeren ble funnet
if ($result->num_rows === 1) {
    $arbeidsgiver = $result->fetch_assoc();
    $arbeidsgiver_id = $arbeidsgiver['id'];

    echo "<h1>Administrer dine jobbannonser " . $_SESSION['username'] . "!</h1>";

    // Henter jobbannonser for den innloggede arbeidsgiveren
    $sql_select_annonser = "SELECT * FROM jobbannonser WHERE arbeidsgiver_id = ?";
    $stmt_select = $conn->prepare($sql_select_annonser);
    $stmt_select->bind_param("i", $arbeidsgiver_id);
    $stmt_select->execute();
    $result_annonser = $stmt_select->get_result();

    // Viser tabell med jobbannonser hvis de finnes
    if ($result_annonser->num_rows > 0) {
        echo "<table style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background-color: #f2f2f2;'><th>ID</th><th>Tittel</th><th>Beskrivelse</th><th>Vis søkere</th></tr>";

        while ($row = $result_annonser->fetch_assoc()) {
            echo "<tr>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $row["id"] . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $row["tittel"] . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $row["beskrivelse"] . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'><a href='sokere.php?jobbannonse_id=" . $row["id"] . "'>Vis detaljer</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Ingen jobbannonser funnet.";
    }

    $stmt_select->close();
} else {
    echo "Feil: Fant ikke arbeidsgiveren i databasen.";
}

