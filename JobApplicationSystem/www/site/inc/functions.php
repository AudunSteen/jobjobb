<?php
// functions.php

// Simulert funksjon for å hente jobboppføring basert på ID
function getJobListingById($jobId)
{
    // Simulert jobbdata fra en database
    $jobListings = [
        1 => ['title' => 'Jobb 1', 'description' => 'Beskrivelse for jobb 1'],
        2 => ['title' => 'Jobb 2', 'description' => 'Beskrivelse for jobb 2'],
        // Legg til flere jobboppføringer her
    ];

    // Sjekk om jobboppføringen eksisterer
    if (array_key_exists($jobId, $jobListings)) {
        return $jobListings[$jobId];
    } else {
        // Håndter feil, for eksempel ved å returnere en tom oppføring eller en feilmelding
        return ['title' => 'Jobb ikke funnet', 'description' => 'Den angitte jobben ble ikke funnet.'];
    }
}
