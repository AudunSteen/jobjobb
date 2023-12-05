<?php
session_start();

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['username']) || $_SESSION['userType'] !== 'arbeidsgiver') {
    header("Location: login.php"); // Send brukeren tilbake til innloggingssiden hvis ikke logget inn eller ikke er arbeidsgiver
    exit();
}

include 'inc/header.php';

$server = "localhost";
$brukernavn = "root";
$passord = "";
$database = "is115DB";

// Opprett tilkobling
$conn = new mysqli($server, $brukernavn, $passord, $database);

// Sjekk tilkoblingen
if ($conn->connect_error) {
    die("Tilkobling mislyktes: " . $conn->connect_error);
}

// Legg til en jobbannonse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tittel = $_POST['tittel'];
    $beskrivelse = $_POST['beskrivelse'];
    $publiseringsdato = date('Y-m-d');
    $interesse = $_POST['interesse'];
    $soknadsfrist = $_POST['soknadsfrist'];

    // Hent arbeidsgiverens ID fra databasen basert på brukernavnet i sesjonen
    $arbeidsgiver_username = $_SESSION['username'];
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $arbeidsgiver_username);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $arbeidsgiver = $result->fetch_assoc();
        $arbeidsgiver_id = $arbeidsgiver['id'];

        // Legg til jobbannonse med kobling til arbeidsgiveren
        $sql_insert_annonse = "INSERT INTO jobbannonser (tittel, beskrivelse, publiseringsdato, interesse, soknadsfrist, arbeidsgiver_id) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt_insert = $conn->prepare($sql_insert_annonse);
        $stmt_insert->bind_param("sssssi", $tittel, $beskrivelse, $publiseringsdato, $interesse, $soknadsfrist, $arbeidsgiver_id);
        
        if ($stmt_insert->execute()) {
            echo "Jobbannonse lagt til.";
        } else {
            echo "Feil ved lagging av jobbannonse: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    } else {
        echo "Feil: Fant ikke arbeidsgiveren i databasen.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Legg til jobbannonse</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 20px;
        }

        h1 {
            color: #333;
            margin-top: 0;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        textarea,
        select,
        input[type="date"] {
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

        form {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
      <h1>Velkommen, <?php echo $_SESSION['username']; ?>!</h1>

        <h2>Opprett en ny stillingsannonse</h2>

        <form method="POST">
            <label for="tittel">Tittel:</label>
            <input type="text" name="tittel" required>
            <label for="beskrivelse">Beskrivelse:</label>
            <textarea name="beskrivelse" required></textarea>
            <label for="interesse">Interesse:</label>
            <select name="interesse">
                <option value="IT">IT</option>
                <option value="Administrasjon">Administrasjon</option>
                <option value="Økonomi">Økonomi</option>
            </select>
            <label for="soknadsfrist">Søknadsfrist:</label>
            <input type="date" name="soknadsfrist" required>
            <input type="submit" value="Legg til jobbannonse">
        </form>
    </div>
</body>

</html>
<?php
include 'inc/footer.php';
?>
