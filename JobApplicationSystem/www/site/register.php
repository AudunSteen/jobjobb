<?php
//vasking av data

include 'inc/header.php'; //Navbar og rettigheter

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'is115DB');

$dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

// Definere variabler og gjøre dem tomme slik at de kan lagres
$usernameErr = $passwordErr = $repeatPasswordErr = $phoneNumberErr = $emailErr = "";

// Sjekke om skjemaet har blitt sendt
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Du må skrive inn brukernavnet ditt";
    } else if (empty($_POST["password"]) || strlen($_POST["password"]) < 8) {
        $passwordErr = "Du må skrive inn passordet ditt eller sørge for at det er minst 8 tegn langt";
    } else if (empty($_POST["repeatPassword"]) || $_POST["repeatPassword"] !== $_POST["password"]) {
        $repeatPasswordErr = "Passordene samsvarer ikke";
    } else if (empty($_POST["phoneNumber"]) || strlen($_POST["phoneNumber"]) < 8) {
        $phoneNumberErr = "Du må skrive inn telefonnummeret ditt og sørge for at det er minst 9 sifre langt";
    } else if (empty($_POST["email"]) || (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))) {
        $emailErr = "Du må skrive inn din riktige e-postadresse";
    } else if (isset($_POST["submit"])) {
        // Legger til formdata i arrayen
        $formData = array(
            "username" => $_POST["username"],
            "password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
            "phoneNumber" => $_POST["phoneNumber"],
            "email" => $_POST["email"]
        );

        // Insert data into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, password, phoneNumber, email, userType) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$formData["username"], $formData["password"], $formData["phoneNumber"], $formData["email"], $_POST["userType"]]);



        echo "Brukeren har blitt lagt til med følgende informasjon: <br>";
        echo "Brukernavn: ", $formData["username"];
        echo "<br>";
        echo "Telefonnummer: ", $formData["phoneNumber"];
        echo "<br>";
        echo "E-postadresse: ", $formData["email"];
        echo "<br>";
        echo "Ditt passord har blitt lagret";
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

    <label for="repeatPassword">Gjenta Passord</label><br>
    <input type="password" id="repeatPassword" name="repeatPassword"><br>
    <span class="error"> <?php echo $repeatPasswordErr ?></span><br>

    <label for="phoneNumber">Telefonnummer</label><br>
    <input type="number" id="phoneNumber" name="phoneNumber"><br>
    <span class="error"> <?php echo $phoneNumberErr ?></span><br>

    <label for="email">E-post</label><br>
    <input type="text" id="email" name="email"><br>
    <span class="error"> <?php echo $emailErr ?></span><br>

    <label for="userType">Velg brukergruppe:</label><br>
    <select id="userType" name="userType">
        <option value="jobbsoker">Jobbsøker</option>
        <option value="arbeidsgiver">Arbeidsgiver</option>
    </select><br>


    <input type="submit" name="submit" value="Send inn"><br>
</form>