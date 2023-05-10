<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
// Set up database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "document";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$user_id = $_SESSION['user_id'];
// Retrieve documents data from database
// $sql    = "SELECT * FROM documents inner join users on documents.user_id = users.id
// where documents.user_id = '$user_id'";
$sql    = "SELECT * FROM documents inner join users on documents.user_id = users.id";
// die($sql);
$result = mysqli_query($conn, $sql);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Output documents data in JSON format
echo json_encode(array("data" => $data));

// Close database connection
mysqli_close($conn);
?>