<?php
session_start();
//DETTE ER SIDE HVOR ARBEIDSGIVER SER ANNONSENE SINE
// Sjekk om brukeren er logget inn og er arbeidsgiver
if (!isset($_SESSION['username']) || $_SESSION['userType'] !== 'arbeidsgiver') {
    header("Location: login.php"); // Send brukeren tilbake til innloggingssiden hvis ikke logget inn eller ikke er arbeidsgiver
    exit();
}

include 'inc/header.php';

$server = "localhost";
$brukernavn = "root";
$passord = "";
$database = "jobbsoksystem";

// Opprett tilkobling
$conn = new mysqli($server, $brukernavn, $passord, $database);

// Sjekk tilkoblingen
if ($conn->connect_error) {
    die("Tilkobling mislyktes: " . $conn->connect_error);
}

// Hent arbeidsgiverens ID fra databasen basert på brukernavnet i sesjonen
$arbeidsgiver_username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $arbeidsgiver_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $arbeidsgiver = $result->fetch_assoc();
    $arbeidsgiver_id = $arbeidsgiver['id'];

    // Hent arbeidsgiverens jobbannonser
    $sql_select_annonser = "SELECT * FROM jobbannonser WHERE arbeidsgiver_id = ?";
    $stmt_select = $conn->prepare($sql_select_annonser);
    $stmt_select->bind_param("i", $arbeidsgiver_id);
    $stmt_select->execute();
    $result_annonser = $stmt_select->get_result();

    if ($result_annonser->num_rows > 0) {
        // Vis jobbannonser
        while ($row = $result_annonser->fetch_assoc()) {
            echo "ID: " . $row["id"] . " - Tittel: " . $row["tittel"] . " - Beskrivelse: " . $row["beskrivelse"] . "<br>";
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
