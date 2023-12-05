<?php
include 'inc/header.php'; //Inkluderer navigasjonsmeny og rettigheter
include 'inc/db.inc.php'; //Database tilkobling

$dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;

$logout_message = isset($_GET['logout_message']) ? $_GET['logout_message'] : '';

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

// Definerer variabler og gjør dem tomme slik at de kan lagres
$usernameErr = $passwordErr = "";

// Sjekker om skjemaet er sendt
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Du må skrive inn brukernavnet ditt";
    } else if (empty($_POST["password"])) {
        $passwordErr = "Du må skrive inn passordet ditt";
    } else {
        // Sanitiserer brukerinput for å forhindre SQL-injection
        $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
        $password = $_POST["password"];

        // Henter brukerdata fra DB
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifiser valgt passord mot lagret hash
        if ($user && password_verify($password, $user['password'])) {
            // Start en sesjon og lagre brukernavn, brukerrolle, og bruker-ID
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Henter brukerens userType fra DB og lagrer det i sesjonen
            $stmt_userType = $pdo->prepare("SELECT userType FROM users WHERE username = ?");
            $stmt_userType->execute([$username]);
            $userType = $stmt_userType->fetchColumn();
            $_SESSION['userType'] = $userType;

            // Omdirigeres til dashboard.php
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Feil brukernavn eller passord";
        }
    }
}

include 'inc/footer.php';
?>

<!-- Skjema hvor bruker kan fylle inn informasjonen sin og legger til feilmeldinger fra PHP-koden -->
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