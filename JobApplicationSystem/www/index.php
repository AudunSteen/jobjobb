<?php
// index.php

// Inkluderer header.php for felles headerinnhold
include 'site/inc/header.php';

// Simulert henting av jobboppføringer fra en database
$jobListings = [
    ['title' => 'Jobb 1', 'description' => 'Beskrivelse for jobb 1'],
    ['title' => 'Jobb 2', 'description' => 'Beskrivelse for jobb 2'],
    // Legg til flere jobboppføringer her
];

?>

<div class="container">
    <h1>Velkommen til Jobbsøkesystemet</h1>

    <h2>Ledige Stillinger</h2>

    <?php
    // Loop gjennom jobboppføringer og bruk malen for hver jobb
    foreach ($jobListings as $job) {
        $jobTitle = $job['title'];
        $jobDescription = $job['description'];

        // Inkluder malen for jobboppføringen
        include 'site/templates/job_listing_template.php';
    }
    ?>
</div>

<?php
// Inkluderer footer.php for felles footerinnhold
include 'site/inc/footer.php';
?>