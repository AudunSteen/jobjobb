<?php
session_start();

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Send brukeren tilbake til innloggingssiden hvis ikke logget inn
    exit();
}

include 'inc/header.php';

// Funksjon for å lagre opplastede filer på serveren
function saveUploadedFile($fileInputName, $destinationFolder)
{
    $targetDir = $destinationFolder;
    $targetFile = $targetDir . basename($_FILES[$fileInputName]['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Sjekk om filen allerede eksisterer
    if (file_exists($targetFile)) {
        echo "Beklager, filen eksisterer allerede.";
        $uploadOk = 0;
    }

    // Sjekk filtypen (du kan legge til flere godkjente filtyper etter behov)
    if ($imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx") {
        echo "Beklager, kun PDF, DOC og DOCX-filer er tillatt.";
        $uploadOk = 0;
    }
    
    

    // Sjekk om $uploadOk er satt til 0 av en feil
    if ($uploadOk == 0) {
        echo "Beklager, filen ble ikke lastet opp.";
    } else {
        // Last opp filen hvis alt er i orden
        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFile)) {
            echo "Filen " . htmlspecialchars(basename($_FILES[$fileInputName]['name'])) . " har blitt lastet opp.";
        } else {
            echo "Beklager, det oppstod en feil under opplastingen av filen.";
        }
    }
}

// Behandle skjemaet for filopplasting
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lagre firmaets profilbilde for arbeidsgiver
    if ($_SESSION['userRole'] == 'arbeidsgiver' && isset($_FILES['companyProfileImage'])) {
        saveUploadedFile('companyProfileImage', 'uploads/company_profiles/');
    }

    // Lagre CV for jobbsøker
    if ($_SESSION['userRole'] == 'jobbsoker' && isset($_FILES['cvFile'])) {
        saveUploadedFile('cvFile', 'uploads/jobseeker_cvs/');
    }
}
?>

<h1>Velkommen til din dashboard, <?php echo $_SESSION['username']; ?>!</h1>

<!-- Skjema for filopplasting -->
<form action="" method="post" enctype="multipart/form-data">
    <?php if ($_SESSION['userRole'] == 'arbeidsgiver') : ?>
        <label for="companyProfileImage">Last opp profilbilde for firma:</label>
        <input type="file" name="companyProfileImage" accept=".png, .jpg, .jpeg"><br>
    <?php elseif ($_SESSION['userRole'] == 'jobbsoker') : ?>
        <label for="cvFile">Last opp CV:</label>
        <input type="file" name="cvFile" accept=".pdf, .doc, .docx"><br>
    <?php endif; ?>
    <input type="submit" value="Last opp fil">
</form>

<?php include 'inc/footer.php'; ?>
