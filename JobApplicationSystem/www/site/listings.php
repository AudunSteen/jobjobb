<?php
// job_listing.php

include 'inc/header.php'; //Navbar og rettigheter

// Håndter logikk for å hente og vise en enkelt jobboppføring fra databasen
// For eksempel, hent jobboppføring basert på en ID fra URL-parameteren
$jobId = $_GET['id']; // Du må sørge for at dette er trygt og gyldig
$job = getJobListingById($jobId); // Implementer denne funksjonen i functions.php

?>

<div class="container">
    <h1><?php echo $job['title']; ?></h1>
    <p><?php echo $job['description']; ?></p>
    <!-- Andre detaljer om jobboppføringen -->
</div>

<?php include 'includes/footer.php'; ?>