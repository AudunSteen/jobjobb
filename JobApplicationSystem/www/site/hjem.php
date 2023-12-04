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
$database = "jobbsoksystem";

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

include 'inc/footer.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Din brukerprofil</title>
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

        h2 {
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>


</head>
<body>
    <div class="container">
    <h1><?php echo $_SESSION['username']; ?> sin profil</h1>

        <h2>Legg til en jobbannonse</h2>
        <form method="POST">
            <label for="tittel">Tittel:</label>
            <input type="text" name="tittel" required>
            <br>
            <label for="beskrivelse">Beskrivelse:</label>
            <textarea name="beskrivelse" required></textarea>
            <br>
            <label for="interesse">Interesse:</label>
            <select name="interesse">
                <option value="IT">IT</option>
                <option value="Administrasjon">Administrasjon</option>
                <option value="Økonomi">Økonomi</option>
            </select>
            <br>
            <input type="submit" value="Legg til jobbannonse">
            <label for="soknadsfrist">Søknadsfrist:</label>
            <input type="date" name="soknadsfrist" required>

        </form>
    </div>
</body>
</html>

