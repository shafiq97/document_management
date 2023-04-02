<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Set up database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "document";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve documents data from database
$sql = "SELECT * FROM documents inner join users where documents.user_id = users.id";
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
