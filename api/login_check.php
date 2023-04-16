<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Replace these values with your own database credentials
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "document";

// Get the username and password from the form
$user = $_POST['username'];
$pass = $_POST['password'];

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a query to select the user based on the username
$sql = "SELECT * FROM users WHERE username='$user'";

// Execute the query
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Verify the password
    $row = $result->fetch_assoc();
    if (password_verify($pass, $row['password'])) {
        // Authentication successful, start a session and store user information
        session_start();
        $_SESSION['user_id']  = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email']    = $row['email'];
        $_SESSION['name']     = $row['name'];

        // Check the user's role and redirect accordingly
        if ($row['role'] == 'admin') {
            header("Location: ../views/admin-dashboard.php");
        } else {
            header("Location: ../views/documents.php");
        }
    } else {
        // Authentication failed, redirect back to the login page with an error message
        header("Location: ../login.php?error=1");
    }
} else {
    // Authentication failed, redirect back to the login page with an error message
    header("Location: ../login.php?error=1");
}

// Close the database connection
$conn->close();

?>