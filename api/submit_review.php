<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
// Include your database configuration file
// Set up database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "document";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the reviews data
$doc_id = $_POST['doc_id'];
$user_id = $_SESSION['user_id'];
$review = $_POST['review'];
$rating = $_POST['rating'];

// Insert the review into the database
$query = "INSERT INTO `reviews` (`user_id`, `doc_id`, `review`, `rating`) VALUES ($user_id, $doc_id, '$review', '$rating')";
$result = mysqli_query($conn, $query);

if ($result) {
  // Redirect the user back to the review page
  header('Location: ../views/review.php?doc_id=' . $doc_id);
} else {
  echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
