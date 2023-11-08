<?php
session_start();

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Send brukeren tilbake til innloggingssiden hvis ikke logget inn
    exit();
}

include 'inc/header.php';
?>

<h1>Velkommen til din dashbord, <?php echo $_SESSION['username']; ?>!</h1>

<!-- Legg til annen innhold eller funksjonalitet for dashbordet her -->

<?php include 'inc/footer.php'; ?>