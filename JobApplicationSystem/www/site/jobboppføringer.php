<?php
include 'inc/header.php';

// Koble til databasen
$server = "localhost";
$brukernavn = "root";
$passord = "";
$database = "is115test";

$conn = new mysqli($server, $brukernavn, $passord, $database);

if ($conn->connect_error) {
    die("Tilkobling mislyktes: " . $conn->connect_error);
}

// Hent jobboppføringer fra databasen
$sql = "SELECT * FROM jobbannonser";
$result = $conn->query($sql);

?>

<div class="container">
    <h1>Jobboppføringer</h1>
    <table>
        <tr>
            <th>Tittel</th>
            <th>Beskrivelse</th>
            <th>Publiseringsdato</th>
            <th>Interesse</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["tittel"] . "</td>";
                echo "<td>" . $row["beskrivelse"] . "</td>";
                echo "<td>" . $row["publiseringsdato"] . "</td>";
                echo "<td>" . $row["interesse"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Ingen jobboppføringer tilgjengelig.</td></tr>";
        }
        ?>
    </table>
</div>

<?php include 'inc/footer.php'; ?>


<!DOCTYPE html>
<html>
<head>
    <title>Jobboppføringer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:nth-child(odd) {
            background-color: #fff;
        }
    </style>
</head>

</html>

