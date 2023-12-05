<?php
//FIKSE AT PDF BLIR KNYTTET OPP MOT BRUKER OG LAGT TIL I DATABASEN 

//cd inn i www
//chmod -R 777 soknader
session_start();

include 'inc/header.php'; //Inkluderer navigasjonsmeny og rettigheter
include 'inc/db.inc.php'; //Database tilkobling

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobbannonse_id = $_POST['jobbannonse_id'];
    $jobbsoker_id = $_SESSION['user_id'];
    $soknadstekst = $_POST['soknadstekst'];

    $pdf_name = $_FILES['pdf']['name'];
    $pdf_tmp_name = $_FILES['pdf']['tmp_name'];
    $pdf_destination = "soknader/" . $pdf_name;

    if (!file_exists("soknader/")) {
        mkdir("soknader/", 0777, true);
    }

    if (move_uploaded_file($pdf_tmp_name, $pdf_destination)) {
        $sql_insert_soknad = "INSERT INTO soknader (jobbannonse_id, jobbsoker_id, soknadstekst, soknadsdato, pdf_path) VALUES (?, ?, ?, CURDATE(), ?)";
        $stmt = $conn->prepare($sql_insert_soknad);
        $stmt->bind_param("iiss", $jobbannonse_id, $jobbsoker_id, $soknadstekst, $pdf_destination);

        if ($stmt->execute()) {
            echo "Søknad lagt til.";
        } else {
            echo "Feil ved lagging av søknad: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Feil ved flytting av PDF-fil til destinasjon.";
    }
}

include 'inc/footer.php';
