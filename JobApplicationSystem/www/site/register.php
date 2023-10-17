<?php
// register.php

include 'inc/header.php';

// Håndter registreringsskjema
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Implementer registreringssjekk her
    // For eksempel, lagre brukernavn og passord i databasen
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simulert registreringssjekk
    // Legg til riktig logikk basert på din implementasjon
    $registration_successful = true;

    if ($registration_successful) {
        // Omdiriger til innloggingssiden etter vellykket registrering
        header('Location: login.php');
        exit;
    } else {
        $error_message = 'Registrering mislyktes. Vennligst prøv igjen.';
    }
}
?>

<div class="container">
    <h1>Registrer deg</h1>

    <?php if (isset($error_message)) : ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="username">Brukernavn:</label>
        <input type="text" name="username" required>

        <label for="password">Passord:</label>
        <input type="password" name="password" required>

        <button type="submit">Registrer deg</button>
    </form>
</div>

<?php include 'inc/footer.php'; ?>