<?php
// dashboard.php

include 'includes/header.php';

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['user'])) {
    // Omdiriger til innloggingssiden hvis brukeren ikke er logget inn
    header('Location: login.php');
    exit;
}

// Hent brukerinformasjon fra databasen eller sesjonen
$userName = $_SESSION['user']; // Dette er en forenklet versjon; implementer riktig brukerhenting

?>

<div class="container">
    <h1>Velkommen til ditt instrumentbord, <?php echo $userName; ?>!</h1>
    <!-- Instrumentbordinnhold -->
</div>

<?php include 'includes/footer.php'; ?>