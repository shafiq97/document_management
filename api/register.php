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
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

// Prepare and execute query
$stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $email);
$stmt->execute();

// Check for errors
if ($stmt->error) {
    echo "Error: " . $stmt->error;
} else {
    echo "User registered successfully!";
    header('login.php?message=success');
}

// Close statement and database connection
$stmt->close();
$conn->close();
?>
