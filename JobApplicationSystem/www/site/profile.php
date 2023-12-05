<?php
session_start();

include 'inc/header.php'; //Navbar og rettigheter
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
        if (!empty($_FILES["profilePicture"]["name"])) {
            // Handle file upload logic here
        }

        // Handle CV upload
        if (!empty($_FILES["cvFile"]["name"])) {
            // Handle CV upload logic here
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
            padding: 20px;
        }

        h2 {
            color: #333;
            margin-top: 0;
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <h2><?php echo $_SESSION['username']; ?> Profile
        <!-- Display current Profile Picture -->
        <?php
        if (isset($profilePicture)) {
            echo "<p>Profilbilde:</p>";
            echo "<img src='$profilePicture' alt='Profile Picture' width='150' height='150'>";
        }
        ?>

        <!-- Display current CV File -->
        <?php
        if (isset($cvFile)) {
            echo "<p>CV: <a href='$cvFile' target='_blank'>Download CV</a></p>";
        }
        ?>

        <!-- Display current Personal Information -->
        <?php
        if (isset($personalInfo)) {
            echo "<p>Personlig info: $personalInfo</p>";
        }
        ?>
    </h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="profilePicture">Profile Picture:</label>
        <input type="file" name="profilePicture" id="profilePicture" accept="image/*">

        <label for="cvFile">Upload CV (PDF only):</label>
        <input type="file" name="cvFile" id="cvFile" accept=".pdf">

        <label for="personalText">Personal Information:</label>
        <textarea name="personalText" id="personalText" rows="4" cols="50"><?php echo isset($personalInfo) ? htmlspecialchars($personalInfo) : ''; ?></textarea>

        <input type="submit" value="Save">
    </form>
</body>

</html>