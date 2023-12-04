<?php
session_start();

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Send brukeren tilbake til innloggingssiden hvis ikke logget inn
    exit();
}

include 'inc/header.php';

$server = "localhost";
$brukernavn = "root";
$passord = "";
$database = "toreTest";

// Opprett tilkobling
$conn = new mysqli($server, $brukernavn, $passord, $database);

// Sjekk tilkoblingen
if ($conn->connect_error) {
    die("Tilkobling mislyktes: " . $conn->connect_error);
}

// Sjekk om skjemaet har blitt sendt
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitProfile"])) {
    // Sjekk om brukeren er logget inn
    if (!isset($_SESSION['username'])) {
        // Legg til logikk for å håndtere dette scenariet, for eksempel omdirigere til innloggingssiden
        echo "Ikke logget inn!";
        exit();
    }

    // Håndter profilbilde
    $profilePicture = $_FILES['profilePicture']['name'];
    $profilePictureTemp = $_FILES['profilePicture']['tmp_name'];
    $profilePicturePath = "uploads/" . $profilePicture; // Endret stien for å lagre i "uploads" mappen
    move_uploaded_file($profilePictureTemp, $profilePicturePath);

    // Håndter personlig informasjon og CV-fil
    $personalInfo = $_POST['personalInfo'];
    $cvFile = $_FILES['cvFile']['name'];
    $cvFileTemp = $_FILES['cvFile']['tmp_name'];
    $cvFilePath = "uploads/" . $cvFile; // Endret stien for å lagre i "uploads" mappen
    move_uploaded_file($cvFileTemp, $cvFilePath);

    // Lagre informasjon i databasen (tilpass med din databasestruktur)
    $UID = $_SESSION['username'];

    $insertQuery = "INSERT INTO users (profile_picture, personal_info, cv_file) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("isss", $username, $profilePicturePath, $personalInfo, $cvFilePath); // Endret til å lagre stien, ikke bare filnavnet

    if ($stmt->execute()) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Feil ved lagring av profilinformasjon: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobbsøkerprofil</title>
    <!-- Legg til eventuelle stiler eller skript du trenger -->
</head>

<body>
    <h1>Jobbsøkerprofil</h1>

    <!-- Skjema for profilinformasjon -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="profilePicture">Profilbilde:</label>
        <input type="file" name="profilePicture" accept="image/*"><br>

        <label for="personalInfo">Personlig informasjon:</label>
        <textarea name="personalInfo" rows="4" cols="50"></textarea><br>

        <label for="cvFile">Last opp CV:</label>
        <input type="file" name="cvFile" accept=".pdf, .doc, .docx"><br>

        <input type="submit" name="submitProfile" value="Lagre profil">
    </form>

    <!-- Legg til oversikt over søkte jobber med status her -->

</body>

</html>