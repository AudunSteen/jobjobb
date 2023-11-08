<?php
require_once('db.inc.php');

$sql = "UPDATE users 
        SET zip = :zip, city = :city 
        WHERE UID = :uid";

$q = $pdo->prepare($sql);
$q->bindParam(':zip', $zip, PDO::PARAM_INT);
$q->bindParam(':city', $city, PDO::PARAM_STR);
$q->bindParam(':uid', $uid, PDO::PARAM_INT);

$zip = 9417;
$city = "Harstad";
$uid = 1;

try {
    $q->execute();
} catch (PDOException $e) {
    echo "Error querying database: " . $e->getMessage() . "<br>"; // Aldri gjør dette i produksjon!
}
//$q->debugDumpParams();

if ($q->rowCount() > 0) {
    echo $q->rowCount() . " record" . ($q->rowCount() != 1 ? "s were " : " was ") . "updated.";
} elseif ($q->rowCount() == 0) {
    echo "The record was not updated (no change).";
} else {
    echo "The record was not updated.";
}
