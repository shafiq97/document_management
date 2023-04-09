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

// Retrieve documents data from database
$sql = "SELECT DISTINCT status, COUNT(*) AS count
FROM documents
GROUP BY status
ORDER BY count DESC";

$result = mysqli_query($conn, $sql);

$data   = array();
$labels = array();
$count  = array();

while ($row = mysqli_fetch_assoc($result)) {
  $labels[] = $row['status'];
  $count[]  = $row['count'];
}

$data['labels'] = $labels;
$data['count']  = $count;

?>

<!DOCTYPE html>
<html>
<head>
  <title>Documents</title>
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
  <?php if (isset($alertClass)): ?>
    <div class="<?php echo $alertClass; ?>"><?php echo $alertMessage; ?></div>
  <?php endif; ?>
  <div class="container" style="padding-top: 3vh">
    <table id="documents-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Owner</th>
          <th>Date</th>
          <th>Description</th>
          <th>Type</th>
          <th>Author</th>
          <th>Keywords</th>
          <th>Status</th>
          <th>File Name</th>
          <th>File Path</th>
          <th>Created At</th>
          <th>Download</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
<h1 style="max-width: 50%; margin: 0 auto; text-align: center; margin-top: 20px;">Summary of document status</h1>
  <div style="max-width: 50%; margin: 0 auto;">
    <canvas id="myChart"></canvas>
  </div>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      var table = $('#documents-table').DataTable({
        "ajax": "documents-data.php",
        "columns": [
          { "data": "id" },
          { "data": "title" },
          {
            "data": "name",
            "render": function (data, type, row) {
              return '<a href="profile-user.php?id=' + row.user_id + '">' + row.name + '</a>';
            }
          },
          { "data": "date" },
          { "data": "description" },
          { "data": "type" },
          { "data": "author" },
          { "data": "keywords" },
          { "data": "status" },
          { "data": "filename" },
          { "data": "filepath" },
          { "data": "created_at" },
          {
            "data": "filepath",
            "render": function (data, type, row) {
              return '<a href="../api/' + row.filepath + '" download="' + row.filename + '">Download</a>';
            },
            "type": "file"
          },

          {
            "data": "doc_id",
            "render": function (data, type, row) {
              return '<a class="btn btn-primary" href="edit-document.php?id=' + row.doc_id + '">Edit</a> <button class="btn btn-danger delete-btn" data-id="' + row.doc_id + '">Delete</button><a class="btn btn-warning" href="review.php?doc_id=' + row.doc_id + '">Review</a>';
            }
          },
        ]
      });
      $('#status-filter').change(function () {
        var status = $(this).val();
        if (status) {
          table.columns(7).search(status).draw();
        } else {
          table.columns(7).search('').draw();
        }
      });
      $('#type-filter').change(function () {
        var status = $(this).val();
        if (status) {
          table.columns(4).search(status).draw();
        } else {
          table.columns(4).search('').draw();
        }
      });

      $('#documents-table tbody').on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        // Redirect to edit page with document id as query parameter
        window.location.href = 'edit-document.php?id=' + id;
      });


      // Handle delete button click
      $('#documents-table tbody').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        swal({
          title: "Are you sure?",
          text: "You will not be able to recover this document!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                url: '../api/delete-document.php',
                type: 'POST',
                data: { id: id },
                success: function (response) {
                  if (response === 'success') {
                    swal("Document deleted successfully!", {
                      icon: "success",
                    }).then(function () {
                      $('#documents-table').DataTable().ajax.reload();
                    });
                  } else {
                    swal("Failed to delete document. Please try again later.", {
                      icon: "error",
                    });
                  }
                }
              });
            }
          });
      });
    });
  </script>
  <script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: <?php echo json_encode($data['labels']); ?>,
        datasets: [{
          label: 'Document Status',
          data: <?php echo json_encode($data['count']); ?>,
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)', // Red
            'rgba(54, 162, 235, 0.2)', // Blue
            'rgba(255, 206, 86, 0.2)' // Yellow
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });


  </script>
</body>
</html>