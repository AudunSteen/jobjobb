<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'inc/header.php';

$server = "localhost";
$username = "root";
$password = "";
$database = "is115DB";

// Create a connection
$conn = new mysqli($server, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
        $targetFile = ""; // Initialize $targetFile

        if (!empty($_FILES["profilePicture"]["name"])) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES["profilePicture"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["profilePicture"]["size"] > 5000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow only certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // If everything is ok, try to upload file
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
                    echo "The file " . htmlspecialchars(basename($_FILES["profilePicture"]["name"])) . " has been uploaded.";

                    // Save profile picture file path to the database
                    $conn->query("UPDATE users SET profilePicture = '$targetFile' WHERE id = $userId");
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }

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
</head>

<body>
    <h2>Profile Page</h2>

    <?php
    if (isset($profilePicture)) {
        echo "<img src='$profilePicture' alt='Profile Picture' width='150' height='150'>";
    }

    if (isset($personalInfo)) {
        echo "<p>Personal Information: $personalInfo</p>";
    }

    if (isset($cvFile)) {
        echo "<p>CV: <a href='$cvFile' target='_blank'>Download CV</a></p>";
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="profilePicture">Profile Picture:</label>
        <input type="file" name="profilePicture" id="profilePicture" accept="image/*">
        <br>

        <label for="cvFile">Upload CV (PDF only):</label>
        <input type="file" name="cvFile" id="cvFile" accept=".pdf">
        <br>

        <label for="personalText">Personal Information:</label>
        <textarea name="personalText" id="personalText" rows="4" cols="50"><?php echo isset($personalInfo) ? htmlspecialchars($personalInfo) : ''; ?></textarea>
        <br>

        <input type="submit" value="Save">
    </form>
</body>

</html>