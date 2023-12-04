<?php
include 'inc/header.php';

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Koble til databasen
$server = "localhost";
$brukernavn = "root";
$passord = "";
$database = "is115DB";

$conn = new mysqli($server, $brukernavn, $passord, $database);

if ($conn->connect_error) {
    die("Tilkobling mislyktes: " . $conn->connect_error);
}

// Hent annonseinformasjon basert på annonse-ID fra URL-parameteren
$annonce_id = $_GET['id'];
$sql_annonse = "SELECT * FROM jobbannonser WHERE id = $annonce_id";
$result_annonse = $conn->query($sql_annonse);

if ($result_annonse->num_rows > 0) {
    $row_annonse = $result_annonse->fetch_assoc();
    ?>

    <div class="container">
        <h1>Detaljer om annonse: <?php echo $row_annonse['tittel']; ?></h1>

        <p><strong>Beskrivelse:</strong> <?php echo $row_annonse['beskrivelse']; ?></p>
        <p><strong>Publiseringsdato:</strong> <?php echo $row_annonse['publiseringsdato']; ?></p>
        <p><strong>Interesse:</strong> <?php echo $row_annonse['interesse']; ?></p>
        <p><strong>Søknadsfrist:</strong> <?php echo $row_annonse['soknadsfrist']; ?></p>

        <!-- Skjema for å laste opp søknad -->
        <form action="handle_soknad.php" method="post" enctype="multipart/form-data">
            <br>
            <label for="pdf">Last opp PDF-søknad:</label>
            <input type="file" name="pdf" accept=".pdf" required>
            <br>
            <input type="hidden" name="jobbannonse_id" value="<?php echo $annonce_id; ?>">
            <input type="submit" value="Send søknad">
        </form>
    </div>

    <?php
} else {
    echo "Annonse ikke funnet.";
}

include 'inc/footer.php';
?>
