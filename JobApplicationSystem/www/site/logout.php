<?php
// Start sesjonen hvis den ikke allerede er startet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sjekk om brukeren er logget inn
if (isset($_SESSION['username'])) {
    // Logg ut brukeren
    session_unset();
    session_destroy();

// Omdiriger til innloggingssiden med en logg ut-melding
header("Location: login.php?logout_message= mikke mus stemme: Heisann sveisann du har blitt logget ut yahho ");
exit();

} else {
    // Hvis brukeren allerede er logget ut, omdiriger til innloggingssiden
    header("Location: login.php");
    exit();
}

// Logg ut brukeren
session_destroy();



?>
