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
  <?php include('header.php') ?>
  <?php if (isset($alertClass)): ?>
    <div class="<?php echo $alertClass; ?>"><?php echo $alertMessage; ?></div>
  <?php endif; ?>
  <div class="container" style="padding-top: 3vh">
    <div class="row">
      <div class="col-md-3">
        <select id="status-filter" class="form-control">
          <option value="">Filter by status</option>
          <option value="publish">Published</option>
          <option value="unpublish">Unpublished</option>
          <option value="draft">Draft</option>
          <option value="archived">Archived</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <select id="type-filter" class="form-control">
          <option value="">Filter by type</option>
          <option value="published">Published</option>
          <option value="unpublish">Unpublished</option>
          <option value="draft">Draft</option>
          <option value="archived">Archived</option>
        </select>
      </div>
    </div>
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

  <canvas id="myChart"></canvas>


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
    var chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Published', 'Unpublished', 'Draft', 'Archived'],
        datasets: [{
          label: 'Number of Documents',
          data: [10, 5, 7, 3],
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)'
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