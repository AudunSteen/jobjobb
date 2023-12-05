<?php
session_start();

include 'inc/header.php'; //Inkluderer navigasjonsmeny og rettigheter
include 'inc/db.inc.php'; //Database tilkobling
include 'inc/session.php'; //Sjekker om bruker er logget inn

// Retrieve the original username of the logged-in user
$originalUsername = $_SESSION['username'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the user ID of the logged-in user
    $result = $conn->query("SELECT id FROM users WHERE username = '$originalUsername'");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userId = $row['id'];

        // Handle profile picture upload
        // ... (your existing code for handling profile picture upload)

        // Handle CV upload
        if (!empty($_FILES["cvFile"]["name"])) {
            $cvTargetDir = "uploads/";
            $cvTargetFile = $cvTargetDir . basename($_FILES["cvFile"]["name"]);
            $cvUploadOk = 1;
            $cvFileType = strtolower(pathinfo($cvTargetFile, PATHINFO_EXTENSION));

            // Check file size
            if ($_FILES["cvFile"]["size"] > 5000000) {
                echo "Sorry, your CV file is too large.";
                $cvUploadOk = 0;
            }

            // Allow only PDF files
            if ($cvFileType != "pdf") {
                echo "Sorry, only PDF files are allowed for CV uploads.";
                $cvUploadOk = 0;
            }

            // If everything is ok, try to upload file
            if ($cvUploadOk == 1) {
                if (move_uploaded_file($_FILES["cvFile"]["tmp_name"], $cvTargetFile)) {
                    echo "The CV file " . htmlspecialchars(basename($_FILES["cvFile"]["name"])) . " has been uploaded.";

                    // Save CV file path to the database
                    $conn->query("UPDATE users SET cvFile = '$cvTargetFile' WHERE id = $userId");
                } else {
                    echo "Sorry, there was an error uploading your CV file.";
                }
            }
        }

        // Save personal information text to the database
        $personalInfo = $_POST['personalText'];

        // Use proper database connection and sanitization methods to prevent SQL injection
        $conn->query("UPDATE users SET information = '$personalInfo' WHERE id = $userId");
    }
}

// Retrieve user information for display
$result = $conn->query("SELECT * FROM users WHERE username = '$originalUsername'");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $profilePicture = $row['profilePicture'];
    $personalInfo = $row['information'];
    $cvFile = $row['cvFile'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1rem;
        }

        h2 {
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        img {
            display: block;
            margin: 0 auto;
            border-radius: 50%;
        }

        p {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2><?php echo $_SESSION['username']; ?> sin profil</h2>

    <?php if (isset($profilePicture)) : ?>
        <img src='<?php echo $profilePicture; ?>' alt='Profile Picture' width='150' height='150'>
    <?php endif; ?>

    <?php if (isset($personalInfo)) : ?>
        <p>Personlig informasjon: <?php echo $personalInfo; ?></p>
    <?php endif; ?>

    <?php if (isset($cvFile)) : ?>
        <p>CV: <a href='<?php echo $cvFile; ?>' target='_blank'>Last ned</a></p>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="profilePicture">Profilbilde:</label>
        <input type="file" name="profilePicture" id="profilePicture" accept="image/*">
        <br>

        <label for="cvFile">Last opp CV (Bare PDF tilatt):</label>
        <input type="file" name="cvFile" id="cvFile" accept=".pdf">
        <br>

        <label for="personalText">Personlig informasjon:</label>
        <textarea name="personalText" id="personalText" rows="4" cols="50"><?php echo isset($personalInfo) ? htmlspecialchars($personalInfo) : ''; ?></textarea>
        <br>

        <input type="submit" value="Save">
    </form>
</body>

</html>