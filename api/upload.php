<?php
// Set up database connection
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(-1);
error_reporting(E_ALL);
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "document";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the file information
    $fileTitle    = mysqli_real_escape_string($conn, $_POST['title']);
    $fileDate     = mysqli_real_escape_string($conn, $_POST['date']);
    $fileDesc     = mysqli_real_escape_string($conn, $_POST['description']);
    $fileType     = mysqli_real_escape_string($conn, $_POST['type']);
    $fileAuthor   = mysqli_real_escape_string($conn, $_POST['author']);
    $fileKeywords = mysqli_real_escape_string($conn, $_POST['keywords']);
    $fileStatus   = mysqli_real_escape_string($conn, $_POST['status']);
    $fileUserId   = mysqli_real_escape_string($conn, $_POST['user_id']);
    $fileName     = $_FILES['file']['name'];
    $fileTempName = $_FILES['file']['tmp_name'];
    $fileSize     = $_FILES['file']['size'];
    $fileError    = $_FILES['file']['error'];
    $fileType     = $_FILES['file']['type'];

    // Check if file was uploaded successfully
    if ($fileError === UPLOAD_ERR_OK) {
        // Create a unique filename to prevent overwriting existing files
        $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid('', true) . '.' . $fileExt;
        $fileDest    = 'uploads/' . $newFileName;

        // Move the file to the uploads folder
        move_uploaded_file($fileTempName, $fileDest);

        // Insert the file information into the database
        $sql = "INSERT INTO documents (title, date, description, type, author, keywords, status, filename, filepath, user_id) 
                VALUES ('$fileTitle', '$fileDate', '$fileDesc', '$fileType', '$fileAuthor', '$fileKeywords', '$fileStatus', '$fileName', '$fileDest', '$fileUserId')";
        if (mysqli_query($conn, $sql)) {
            // Redirect to a success page
            header('Location: success.php');
            exit;
        } else {
            // Handle database insert errors
            // You could display an error message or redirect to an error page
        }
    } else {
        // Handle file upload errors
        // You could display an error message or redirect to an error page
    }
}

// Close database connection
mysqli_close($conn);
?>