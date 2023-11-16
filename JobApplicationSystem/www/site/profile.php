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
$database = "is115test";

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
    $soknadsfrist = $_POST['soknadsfrist']; // Legg til denne linjen

    $sql_insert_annonse = "INSERT INTO jobbannonser (tittel, beskrivelse, publiseringsdato, interesse, soknadsfrist) VALUES ('$tittel', '$beskrivelse', '$publiseringsdato', '$interesse', '$soknadsfrist')";
    $conn->query($sql_insert_annonse);
}


?>


<?php include 'inc/footer.php'; ?>

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

