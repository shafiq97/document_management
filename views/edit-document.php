<?php
// Set up database connection
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(-1);
error_reporting(E_ALL);
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "document";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the file information and update the database
  // ...
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Check if an ID value was passed in the URL
  if (isset($_GET['id'])) {
    // Retrieve the document information with the specified ID value
    $id       = mysqli_real_escape_string($conn, $_GET['id']);
    $sql      = "SELECT * FROM documents WHERE doc_id = '$id'";
    $result   = mysqli_query($conn, $sql);
    $document = mysqli_fetch_assoc($result);

    // Display the edit form with the retrieved document information
    // include('edit-document.php');
  }
}

// Close database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
  <title>Edit Document Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <?php include('header.php') ?>
  <div class="container">
    <h1>Edit Document Form</h1>
    <form action="../api/update-document.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $document['doc_id'] ?>">
      <div class="form-group">
        <label for="title">Document Title:</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo $document['title'] ?>">
      </div>
      <div class="form-group">
        <label for="date">Date:</label>
        <input type="date" class="form-control" id="date" name="date" value="<?php echo $document['date'] ?>">
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" id="description"
          name="description"><?php echo $document['description'] ?></textarea>
      </div>
      <div class="form-group">
        <label for="type">Type of Document:</label>
        <input type="text" class="form-control" id="type" name="type" value="<?php echo $document['type'] ?>">
      </div>
      <div class="form-group">
        <label for="author">Author:</label>
        <input type="text" class="form-control" id="author" name="author" value="<?php echo $document['author'] ?>">
      </div>
      <div class="form-group">
        <label for="keywords">Annotation Keywords:</label>
        <input type="text" class="form-control" id="keywords" name="keywords"
          value="<?php echo $document['keywords'] ?>">
      </div>
      <div class="form-group">
        <label for="status">Status:</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="status" id="draft" value="draft" <?php if ($document['status'] == 'draft')
            echo 'checked' ?>>
            <label class="form-check-label" for="draft">
              Draft
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="publish" value="publish" <?php if ($document['status'] == 'publish')
            echo 'checked' ?>>
            <label class="form-check-label" for="publish">
              Publish
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="unpublish" value="unpublish" <?php if ($document['status'] == 'unpublish')
            echo 'checked' ?>>
            <label class="form-check-label" for="unpublish">
              Unpublish
            </label>
          </div>
        </div>
        <div class="form-group">

          <label for="file">Select file to upload:</label>
          <input type="file" class="form-control-file" id="file" name="file">
          <small>Leave this field blank if you do not want to change the file.</small>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </form>
    </div>