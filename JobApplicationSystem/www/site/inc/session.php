<?php
// Sjekk om brukeren er logget inn
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Omdiriger brukeren til innloggingssiden hvis de ikke er logget inn
    exit();
}
