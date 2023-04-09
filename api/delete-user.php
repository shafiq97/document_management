<?php
if (isset($_POST['id'])) {
  // Set up database connection
  $servername = "localhost";
  $username   = "root";
  $password   = "";
  $dbname     = "document";

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Prepare and execute SQL query to delete user
  $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
  $stmt->bind_param("i", $_POST['id']);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    // User deleted successfully
    header("Location: ../views/users.php?message=success");
    exit();
  } else {
    // User not found
    header("Location: ../views/users.php?message=User not found");
    exit();
  }
} else {
  // ID parameter not provided
  header("Location: ../views/users.php?message=Invalid request");
  exit();
}
?>
