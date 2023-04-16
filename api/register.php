<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(-1);
error_reporting(E_ALL);

// Database configuration
$dbHost     = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "document";

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$username        = $_POST['username'];
$password        = $_POST['password'];
$email           = $_POST['email'];
$role            = $_POST['role'];
$role2           = "user";
$course          = $_POST['course'];
$programme       = $_POST['programme'];
$name            = $_POST['name'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

if (isset($_FILES['photo'])) {
    $targetDir     = "user_uploads/";
    $targetFile    = $targetDir . basename($_FILES['photo']['name']);
    $uploadOk      = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file size
    if ($_FILES["photo"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            echo "The file " . basename($_FILES["photo"]["name"]) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $image_path = $targetFile;
}


// Prepare and execute query
$stmt = $conn->prepare("INSERT INTO users (username, password, email, status, role, course, programme, name, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $username, $hashed_password, $email, $role, $role2, $course, $programme, $name, $image_path);
$stmt->execute();

// Check for errors
if ($stmt->error) {
    echo "Error: " . $stmt->error;
} else {
    // echo "User registered successfully!";
    header('location: ../login.php?message=success');
}

// Close statement and database connection
$stmt->close();
$conn->close();
?>