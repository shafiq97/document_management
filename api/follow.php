<?php
session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Replace these values with your own database credentials
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "document";

// Get the user ID, following ID, and follower ID from the POST request
$user_id      = $_SESSION['user_id']; // User who is performing the follow/unfollow action
$following_id = $_POST['user_id']; // User whose profile is being followed/unfollowed

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the user is already following the profile
$sql = "SELECT * FROM follows WHERE following_id = $following_id AND follower_id = $user_id";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
  // The user is not following the profile, so insert a new follow record
  $sql = "INSERT INTO follows (user_id, following_id, follower_id) VALUES ($user_id, $following_id, $user_id)";

  if ($conn->query($sql) === true) {
    // Follow action successful
    echo "Unfollow";
  } else {
    // Failed to insert the follow record
    echo "error";
  }
} else {
  // The user is already following the profile, so delete the existing follow record
  $sql = "DELETE FROM follows WHERE following_id = $following_id AND follower_id = $user_id";

  if ($conn->query($sql) === true) {
    // Unfollow action successful
    echo "unfollowed";
  } else {
    // Failed to delete the follow record
    echo "error";
  }
}

// Close the database connection
$conn->close();

?>
