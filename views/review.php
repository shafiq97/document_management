<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
// Include your database configuration file
// Set up database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "document";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the reviews data
$doc_id  = $_GET['doc_id'];
$user_id = $_SESSION['user_id'];

$query_doc  = "SELECT * from documents WHERE doc_id = $doc_id";
$result_doc = mysqli_query($conn, $query_doc);
$doc_data   = mysqli_fetch_assoc($result_doc);

$query  = "SELECT * FROM reviews inner join users on users.id = reviews.user_id WHERE `doc_id` = $doc_id ";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Review Documents</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>
<body>
  <?php include 'header.php' ?>
  <div class="container" style="padding-top: 3vh">
    <h1>Review Documents</h1>
    <table id="reviews" class="display">
      <thead>
        <tr>
          <th>Document ID</th>
          <th>User Name</th>
          <th>Review</th>
          <th>Rating</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td>
              <?php echo $row['doc_id']; ?>
            </td>
            <td>
              <?php echo $row['name']; ?>
            </td>
            <td>
              <?php echo $row['review']; ?>
            </td>
            <td>
              <?php echo $row['rating']; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <form method="POST" action="../api/submit_review.php">
      <div class="form-group">
        <label for="doc_id">Document ID:</label>
        <input type="text" class="form-control" id="doc_id" name="doc_id" value="<?php echo $_GET['doc_id'] ?>"
          readonly>
      </div>
      <div class="form-group">
        <label for="doc_name">Document Name:</label>
        <input type="text" class="form-control" id="doc_name" name="doc_name" value="<?php echo $doc_data['title']; ?>"
          readonly>
      </div>
      <div class="form-group">
        <label for="rating">Rating:</label>
        <div id="rating"></div>
        <input type="hidden" name="rating" value="">
      </div>
      <div class="form-group">
        <label for="rating">Review:</label>
        <textarea class="form-control" id="review" name="review"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
  </div>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#reviews').DataTable();
      // Initialize the rating plugin
      $("#rating").rateYo({
        starWidth: "20px",
        fullStar: true,
        rating: 0,
        onSet: function (rating, rateYoInstance) {
          $('input[name="rating"]').val(rating);
        }
      });
    });
  </script>
</body>
</html>