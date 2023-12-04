<?php
session_start();

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

$arbeidsgiver_username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $arbeidsgiver_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $arbeidsgiver = $result->fetch_assoc();
    $arbeidsgiver_id = $arbeidsgiver['id'];

    $sql_select_annonser = "SELECT * FROM jobbannonser WHERE arbeidsgiver_id = ?";
    $stmt_select = $conn->prepare($sql_select_annonser);
    $stmt_select->bind_param("i", $arbeidsgiver_id);
    $stmt_select->execute();
    $result_annonser = $stmt_select->get_result();

    if ($result_annonser->num_rows > 0) {
        while ($row = $result_annonser->fetch_assoc()) {
            echo "ID: " . $row["id"] . " - Tittel: " . $row["tittel"] . " - Beskrivelse: " . $row["beskrivelse"];
            echo " <a href='sokere.php?jobbannonse_id=" . $row["id"] . "'>Vis detaljer</a><br>";
        }
    } else {
        echo "Ingen jobbannonser funnet.";
    }

    $stmt_select->close();
} else {
    echo "Feil: Fant ikke arbeidsgiveren i databasen.";
}

include 'inc/footer.php';
?>
