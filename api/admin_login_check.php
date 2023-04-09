<?php
// Replace these values with your own database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "document";

// Get the username and password from the form
$user = $_POST['username'];
$pass = $_POST['password'];

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a query to check if the username and password are correct
$sql = "SELECT * FROM users WHERE username='$user' AND password='$pass'";

// Execute the query
$result = $conn->query($sql);

// Check if there is a matching record in the database
if ($result->num_rows == 1) {
    // Authentication successful, start a session and store user information
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['name'] = $row['name'];
    
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

// Close the database connection
$conn->close();
?> 