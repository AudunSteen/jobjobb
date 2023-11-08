<?php
require_once('db.inc.php');

$sql = "SELECT * 
        FROM users 
        WHERE lastname = :lastname && city = :city";
$q = $pdo->prepare($sql);
$q->bindParam(':lastname', $lastname, PDO::PARAM_STR);
$q->bindParam(':city', $city, PDO::PARAM_STR);

$lastname = "Danielsen";
$city = "Tulleby";

try {
    $q->execute();
} catch (PDOException $e) {
    echo "Error querying database: " . $e->getMessage() . "<br>"; // Aldri gjør dette i produksjon!
}
//$q->debugDumpParams();

$users = $q->fetchAll(PDO::FETCH_OBJ); // Bruk FETCH_ASSOC for å returnere en matrise istedenfor

if ($q->rowCount() > 0) {
    foreach ($users as $user) {
        echo $user->UID . " // ";
        echo $user->reg . " // ";
        echo $user->firstname . " // ";
        echo $user->lastname . " // ";
        echo $user->email . " // ";
        echo $user->zip . " // ";
        echo $user->city . " // ";
        echo $user->email . "<br>";
    }
} else {
    echo "The query resulted in an empty result set.";
}

$sql = "SELECT u.firstname, u.lastname, u.zip, c.city 
        FROM users AS u 
        LEFT JOIN cities AS c ON (u.zip = c.zip) 
        WHERE u.zip > :zip";
$q = $pdo->prepare($sql);
$q->bindParam(':zip', $zip, PDO::PARAM_INT);
$zip = 4500;
