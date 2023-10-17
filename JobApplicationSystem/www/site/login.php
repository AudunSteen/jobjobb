<?php
// login.php

include 'inc/header.php';

// Håndter innloggingsskjema
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Implementer innloggingssjekk her
    // For eksempel, sjekk brukernavn og passord fra skjema mot databasen
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simulert innloggingssjekk
    if (validateUser($username, $password)) {
        // Sett en sesjon og omdiriger til dashboard
        $_SESSION['user'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error_message = 'Feil brukernavn eller passord.';
    }
}

// Simulert funksjon for å validere brukernavn og passord
function validateUser($username, $password)
{
    // Implementer denne funksjonen basert på din brukerhåndteringslogikk
    // For eksempel, sjekk mot en database
    return ($username === 'brukernavn' && $password === 'passord');
}
?>

<div class="container">
    <h1>Logg inn</h1>

    <?php if (isset($error_message)) : ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="username">Brukernavn:</label>
        <input type="text" name="username" required>

        <label for="password">Passord:</label>
        <input type="password" name="password" required>

        <button type="submit">Logg inn</button>
    </form>
</div>

<?php include 'inc/footer.php'; ?>