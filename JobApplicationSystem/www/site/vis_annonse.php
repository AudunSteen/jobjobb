<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'inc/header.php';

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Omdiriger brukeren til innloggingssiden hvis de ikke er logget inn
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

// Hent annonseinformasjon basert på ID fra URL-parameteren
$jobbannonse_id = isset($_GET['id']) ? $_GET['id'] : 0;

$sql = "SELECT * FROM jobbannonser WHERE id = $jobbannonse_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tittel = $row["tittel"];
    $beskrivelse = $row["beskrivelse"];
    $publiseringsdato = $row["publiseringsdato"];
    $interesse = $row["interesse"];
    $soknadsfrist = $row["soknadsfrist"];
    $arbeidsgiver_id = $row["arbeidsgiver_id"];
} else {
    echo "Annonse ikke funnet.";
    exit();
}

// Behandle søknadssubmisjon
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validér og lagre søknaden i databasen
    $jobbsoker_id = $_SESSION['user_id']; // Assuming you have a user_id in the session
    $soknadstekst = $_POST['soknadstekst'];

    // Handle file upload errors
    if ($_FILES['pdf_path']['error'] !== 0) {
        echo "File upload error: " . $_FILES['pdf_path']['error'];
        exit();
    }

    // Continue with the rest of your file upload logic
    $pdf_path = '';
    if (isset($_FILES['pdf_path']) && $_FILES['pdf_path']['error'] === 0) {
        $pdf_path = 'soknader/' . uniqid() . '_' . $_FILES['pdf_path']['name'];
        move_uploaded_file($_FILES['pdf_path']['tmp_name'], $pdf_path);
    }

    // Insert the application into the soknader table
    $insert_sql = "INSERT INTO soknader (jobbannonse_id, jobbsoker_id, soknadstekst, soknadsdato, pdf_path) 
                   VALUES ('$jobbannonse_id', '$jobbsoker_id', '$soknadstekst', NOW(), '$pdf_path')";

    if ($conn->query($insert_sql) === TRUE) {
        echo "Søknad sendt!";
    } else {
        echo "Feil ved innsending av søknad: " . $conn->error;
    }
}
?>

<div class="container">
    <h1><?php echo $tittel; ?></h1>
    <p><strong>Beskrivelse:</strong> <?php echo $beskrivelse; ?></p>
    <p><strong>Publiseringsdato:</strong> <?php echo $publiseringsdato; ?></p>
    <p><strong>Interesse:</strong> <?php echo $interesse; ?></p>
    <p><strong>Søknadsfrist:</strong> <?php echo $soknadsfrist; ?></p>
    <p><strong>Arbeidsgiver ID:</strong> <?php echo $arbeidsgiver_id; ?></p>

    <h2>Søk på denne stillingen</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="soknadstekst">Søknadstekst:</label>
        <textarea name="soknadstekst" required></textarea><br>
        <label for="pdf_path">Last opp CV (PDF):</label>
        <input type="file" name="pdf_path" accept=".pdf"><br>
        <input type="submit" value="Send søknad">
    </form>
</div>

<?php include 'inc/footer.php'; ?>
