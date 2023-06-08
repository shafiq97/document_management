<!DOCTYPE html>
<html>
<head>
  <title>Documents</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
</head>
<style>
  /* Your styles here */
</style>
<body>
  <?php 
    include('header.php'); 

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
    $sql = "SELECT * FROM documents inner join users on documents.user_id = users.id";
    $result = mysqli_query($conn, $sql);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    // Close database connection
    mysqli_close($conn);
  ?>

  <?php if (isset($alertClass)): ?>
    <div class="<?php echo $alertClass; ?>"><?php echo $alertMessage; ?></div>
  <?php endif; ?>

  <div class="container" style="padding-top: 3vh">
    <div class="card-list">
      <?php
        foreach ($data as $document) {
          echo "<div class='card p-3'>";
          echo "<div class='card-title'>" . htmlspecialchars($document['title']) . "</div>";
          echo "<div class='card-subtitle'>Author: " . htmlspecialchars($document['author']) . "</div>";
          echo "<div class='card-text'>" . htmlspecialchars($document['description']) . "</div>";
          echo "<div class='card-actions'>";
          echo "<a class='btn btn-secondary mr-3' href='../api/" . $document['filepath'] . "' download='" . $document['filename'] . "'>Download</a>";
          echo "<a class='btn btn-primary' href='../api/" . $document['filepath'] . "?preview=true'>Preview</a>";
          echo "</div>";
          echo "</div>";
        }
      ?>
    </div>
  </div>
</body>
</html>
