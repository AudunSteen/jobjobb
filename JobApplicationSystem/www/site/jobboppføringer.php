<?php
include 'inc/header.php'; //Inkluderer navigasjonsmeny og rettigheter
include 'inc/db.inc.php'; //Database tilkobling
include 'inc/session.php'; //Sjekker om bruker er logget inn

// Behandler filtervalg
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'alle';
$soknadsfrist = isset($_GET['soknadsfrist']) ? $_GET['soknadsfrist'] : 'asc';

// SQL-spørringer med filter og sortering
$sql = "SELECT * FROM jobbannonser";

if ($filter !== 'alle') {
    $sql .= " WHERE interesse = '$filter'";
}

$sql .= " ORDER BY soknadsfrist $soknadsfrist"; // Sorter etter søknadsfrist (asc eller desc)

$result = $conn->query($sql);

// Hent alle kategorier fra databasen
$sql_categories = "SELECT DISTINCT interesse FROM jobbannonser";
$result_categories = $conn->query($sql_categories);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Jobboppføringer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 20px;
        }

        h1 {
            color: #333;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 12px;
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

        select,
        input {
            padding: 8px;
            margin-right: 10px;
        }

        form {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Jobboppføringer</h1>

        <!-- Filter-skjema -->
        <form method="GET" action="jobboppføringer.php">
            <label for="filter">Filtrer etter kategori:</label>
            <select name="filter" id="filter">
                <option value="alle">Alle</option>
                <?php
                while ($row_category = $result_categories->fetch_assoc()) {
                    $category = $row_category['interesse'];
                    echo "<option value='$category'>$category</option>";
                }
                ?>
            </select>
            <label for="soknadsfrist">Sorter etter søknadsfrist:</label>
            <select name="soknadsfrist" id="soknadsfrist">
                <option value="asc">Dato stigende</option>
                <option value="desc">Dato synkende</option>
            </select>
            <input type="submit" value="Filtrer">
        </form>

        <table>
            <tr>
                <th>Tittel</th>
                <th>Beskrivelse</th>
                <th>Publiseringsdato</th>
                <th>Interesse</th>
                <th>Søknadsfrist</th>
                <th>Søk stilling</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["tittel"] . "</td>";
                    echo "<td>" . $row["beskrivelse"] . "</td>";
                    echo "<td>" . $row["publiseringsdato"] . "</td>";
                    echo "<td>" . $row["interesse"] . "</td>";
                    echo "<td>" . $row["soknadsfrist"] . "</td>";
                    echo "<td><a href='vis_annonse.php?id=" . $row["id"] . "'>Vis detaljer</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Ingen jobboppføringer tilgjengelig.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>
