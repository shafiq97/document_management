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
  // Get the ID value and other form data
  // Update the document information in the database
  $id          = mysqli_real_escape_string($conn, $_POST['id']);
  $title       = mysqli_real_escape_string($conn, $_POST['title']);
  $date        = mysqli_real_escape_string($conn, $_POST['date']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $type        = mysqli_real_escape_string($conn, $_POST['type']);
  $author      = mysqli_real_escape_string($conn, $_POST['author']);
  $keywords    = mysqli_real_escape_string($conn, $_POST['keywords']);
  $status      = mysqli_real_escape_string($conn, $_POST['status']);

  // Check if a new file was uploaded
  if ($_FILES['file']['size'] > 0) {
    $file         = $_FILES['file'];
    $originalName = $file['name'];
    $tmpName      = $file['tmp_name'];
    $extension    = pathinfo($originalName, PATHINFO_EXTENSION);
    // Generate a unique filename and save the uploaded file to the server
    $newFilename = uniqid() . '.' . $extension;
    $destination = '../uploads/' . $newFilename;
    move_uploaded_file($tmpName, $destination);

    // Update the file information in the database
    $sql = "UPDATE documents SET title = '$title', date = '$date', description = '$description', type = '$type', author = '$author', keywords = '$keywords', status = '$status', filename = '$newFilename', filepath = '$destination' WHERE doc_id = '$id'";
  } else {
    // Update the document information without updating the file information in the database
    $sql = "UPDATE documents SET title = '$title', date = '$date', description = '$description', type = '$type', author = '$author', keywords = '$keywords', status = '$status' WHERE doc_id = '$id'";
  }
  // Execute the SQL statement and check for errors
  if (mysqli_query($conn, $sql)) {
    // Redirect to the documents page and display a success message
    header('Location: ../views/documents.php?message=success');
    exit();
  } else {
    // Display an error message if the SQL statement fails
    header('Location: ../views/documents.php?message=' . mysqli_error($conn));
    echo "Error updating document: " . mysqli_error($conn);
  }

  // Close database connection
  mysqli_close($conn);
}
?>