<?php
if (isset($_GET['message'])) {
  if ($_GET['message'] === 'success') {
    $alertClass   = 'alert alert-success';
    $alertMessage = 'Document edited successfully!';
  } else {
    $alertClass   = 'alert alert-danger';
    $alertMessage = $_GET['message'];
  }
}
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Set up database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "document";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Retrieve users data from database
$sql    = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Users</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
  .container {
    max-width: 100vw;
    overflow-x: auto;
  }
</style>
<body>
  <?php include('../admin/header.php') ?>

  <div class="container mt-4">
    <table id="users-table" class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td>
              <?php echo $row['id']; ?>
            </td>
            <td>
              <?php echo $row['name']; ?>
            </td>
            <td>
              <?php echo $row['email']; ?>
            </td>
            <td>
              <button type="button" class="btn btn-danger delete-btn" data-id="<?php echo $row['id']; ?>">Delete</button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#users-table').DataTable();

      // Handle delete button click
      $('.delete-btn').click(function () {
        var userId = $(this).data('id');

        // Display confirmation dialog
        swal({
          title: "Are you sure?",
          text: "You will not be able to recover this user!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) {
            // Send delete request to server
            $.ajax({
              url: "../api/delete-user.php",
              method: "POST",
              data: { id: userId },
              success: function () {
                // Reload page after deleting user
                location.reload();
              }
            });
          }
        });
      });
    });
  </script>
</body>
</html>