<?php
include 'inc/header.php';

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'is115test');

$dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;

$logout_message = isset($_GET['logout_message']) ? $_GET['logout_message'] : '';

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

// Definere variabler og gjøre dem tomme slik at de kan lagres
$usernameErr = $passwordErr = "";

// Sjekke om skjemaet har blitt sendt
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Du må skrive inn brukernavnet ditt";
    } else if (empty($_POST["password"])) {
        $passwordErr = "Du må skrive inn passordet ditt";
    } else {
        // Sanitize user input to prevent SQL injection
        $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
        $password = $_POST["password"];

        // Retrieve user data from the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the entered password against the stored hash
        if ($user && password_verify($password, $user['password'])) {
            echo "Du har logget inn som: " . $user["username"];

            // Verify the entered password against the stored hash
            if ($user && password_verify($password, $user['password'])) {
                // Start en sesjon og lagre brukernavnet
                session_start();
                $_SESSION['username'] = $user['username'];

                // Redirect til dashboard.php
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Feil brukernavn eller passord";
            }

            // You may redirect to another page after successful login
        } else {
            echo "Feil brukernavn eller passord";
        }
    }
}

include 'inc/footer.php';
?>

<!-- Opprette et skjema hvor brukeren kan fylle inn informasjonen sin og legger til feilmeldinger fra PHP-koden -->
<form method="post" action="">

    <label for="username">Brukernavn</label><br>
    <input type="text" id="username" name="username"><br>
    <span class="error"> <?php echo $usernameErr ?></span><br>

    <label for="password">Passord</label><br>
    <input type="password" id="password" name="password"><br>
    <span class="error"> <?php echo $passwordErr ?></span><br>

    <input type="submit" name="submit" value="Logg inn"><br>

    <!-- Vis logg ut-meldingen for brukeren -->
<p><?php echo $logout_message; ?></p>

<!-- Ditt eksisterende skjema for innlogging -->
<form method="post" action="">
    <!-- ... -->
</form>
</form>