<?php
// MySQL database credentials
$host     = "localhost";
$username = "root";
$password = "";
$database = "document_management";

// Create a MySQLi object
$mysqli = new mysqli($host, $username, $password, $database);

// Check for errors
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

// Connection successful
echo "Connected to MySQL database successfully!";

?>