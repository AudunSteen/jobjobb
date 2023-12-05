<?php
// Tillat feilrapportering
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'inc/header.php'; //Inkluderer navigasjonsmeny og rettigheter
include 'inc/db.inc.php'; //Database tilkobling
include 'inc/session.php'; //Sjekker om bruker er logget inn

// Henter annonseinformasjon basert på ID fra URL-parameteren
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
} else {
    echo "Annonse ikke funnet.";
    exit();
}

// Behandler søknadssubmisjon
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validér og lagre søknaden i databasen
    $jobbsoker_id = $_SESSION['user_id']; // Assuming you have a user_id in the session
    $soknadstekst = $_POST['soknadstekst'];

    // Handling ved feil av filopplastning
    if ($_FILES['pdf_path']['error'] !== 0) {
        echo "File upload error: " . $_FILES['pdf_path']['error'];
        exit();
    }

    // genererer en unik ID basert på nåværende tidspunkt, noe som bidrar til å gi filen et unikt navn 
    // for å unngå eventuelle konflikter med eksisterende filer
    $pdf_path = '';
    if (isset($_FILES['pdf_path']) && $_FILES['pdf_path']['error'] === 0) {
        $pdf_path = 'soknader/' . uniqid() . '_' . $_FILES['pdf_path']['name'];
        move_uploaded_file($_FILES['pdf_path']['tmp_name'], $pdf_path);
    }

    // Legger inn søknad i søknadstabellen
    $insert_sql = "INSERT INTO soknader (jobbannonse_id, jobbsoker_id, soknadstekst, soknadsdato, pdf_path) 
                   VALUES ('$jobbannonse_id', '$jobbsoker_id', '$soknadstekst', NOW(), '$pdf_path')";

    if ($conn->query($insert_sql) === TRUE) {
        echo "Søknad sendt!";
    } else {
        echo "Feil ved innsending av søknad: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tittel; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #333;
            margin-top: 0;
        }

        p {
            margin-bottom: 10px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        textarea,
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1><?php echo $tittel; ?></h1>
        <p><strong>Beskrivelse:</strong> <?php echo $beskrivelse; ?></p>
        <p><strong>Publiseringsdato:</strong> <?php echo $publiseringsdato; ?></p>
        <p><strong>Interesse:</strong> <?php echo $interesse; ?></p>
        <p><strong>Søknadsfrist:</strong> <?php echo $soknadsfrist; ?></p>

        <h2>Søk på denne stillingen</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="soknadstekst">Søknadstekst:</label>
            <textarea name="soknadstekst" required></textarea><br>
            <label for="pdf_path">Last opp CV (PDF):</label>
            <input type="file" name="pdf_path" accept=".pdf"><br>
            <input type="submit" value="Send søknad">
        </form>
    </div>

</body>

</html>


<?php include 'inc/footer.php'; ?>