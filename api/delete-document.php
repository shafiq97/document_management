<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Retrieve the ID parameter from the POST request
  $id = $_POST['id'];

  // Connect to the database using PDO
  $host = 'localhost'; // Change this to your MySQL host
  $dbname = 'document'; // Change this to your database name
  $username = 'root'; // Change this to your MySQL username
  $password = ''; // Change this to your MySQL password
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

  // Prepare and execute the DELETE query
  $stmt = $pdo->prepare("DELETE FROM documents WHERE id = ?");
  $stmt->execute([$id]);

  // Check if the query was successful
  if ($stmt->rowCount() > 0) {
    echo 'success';
  } else {
    echo 'error';
  }
}
?>
