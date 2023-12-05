<?php

//starter session for å kunne gi ulike rettigheter i systemet.
session_start();

include 'inc/session.php'; //Sjekker om bruker er logget inn
include 'inc/header.php'; //Inkluderer navigasjonsmeny og rettigheter

// En hyggelig velkomstmelding til brukeren basert om brukeren er arbeidsgiver eller jobbsoker
$userTypeMessage = "";
if ($_SESSION['userType'] === 'arbeidsgiver') {
    $userTypeMessage = "Du er registrert som arbeidsgiver. Velkommen til vårt jobbsøkesystem! Her kan du publisere ledige stillinger, håndtere søknader og finne passende kandidater for dine jobbannonser.";
} elseif ($_SESSION['userType'] === 'jobbsoker') {
    $userTypeMessage = "Du er registrert som jobbsøker. Velkommen til vårt jobbsøkesystem! Her kan du opprette din profil, legge til CV, søke på ledige stillinger, og holde oversikt over dine søknader.";
} else {
    $userTypeMessage = "Velkommen til jobbsøkesystemet, " . $_SESSION['username'] . "!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Velkommen til jobbsøkesystemet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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

        p {
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Velkommen til jobbsøkesystemet, <?php echo $_SESSION['username']; ?>!</h1>
        <p><?php echo $userTypeMessage; ?></p>
    </div>
</body>

</html>

