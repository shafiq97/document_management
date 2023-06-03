<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(-1);
error_reporting(E_ALL);

// Connect to database
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "document";
$conn       = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Get user information from database
$user_id = $_GET['id'];
$sql     = "SELECT * FROM users WHERE id = $user_id";
$result  = mysqli_query($conn, $sql);
$user    = mysqli_fetch_assoc($result);

// Update user information if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name     = mysqli_real_escape_string($conn, $_POST['name']);
  $email    = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  // Check if password was changed
  if (!empty($password)) {
    // $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET name = '$name', email = '$email', password = '$password' WHERE id = $user_id";
  } else {
    $sql = "UPDATE users SET name = '$name', email = '$email' WHERE id = $user_id";
  }

  if (mysqli_query($conn, $sql)) {
    $_SESSION['name'] = $name;
    header("Location: profile.php?success=1");
    exit();
  } else {
    $error = "There was an error updating your profile.";
  }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Profile - My Website</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!-- Custom Styles -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f5f5f5;
    }

    #content-wrapper {
      display: flex;
      flex-wrap: wrap;
      justify-content: flex-start;
      margin-top: 50px;
    }

    .container {
      width: calc(20% - 10px);
      margin-right: 10px;
      margin-left: 20px;
    }

    .container-table {
      width: calc(50% - 10px);
      margin-left: 10px;
      margin-left: 20px;

    }

    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 40px;
      background-color: #fff;
    }

    .card-title {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 30px;
      color: #444;
      text-align: center;
    }

    .form-group label {
      font-weight: bold;
      color: #444;
    }

    .form-control {
      border-radius: 20px;
    }

    .btn {
      border-radius: 20px;
      background-color: #4285f4;
      border-color: #4285f4;
      font-weight: bold;
      transition: all 0.2s ease-in-out
    }

    .btn:hover {
      background-color: #357ae8;
      border-color: #357ae8;
      transform: scale(1.05);
    }

    .alert {
      margin-top: 20px;
      text-align: center;
    }
  </style>
</head>
<body>
  <?php include('header.php') ?>
  <div id='content-wrapper'>
    <div class="container">
      <div class="card">
        <h1 class="card-title">Profile</h1>
        <?php if (isset($error)): ?>
          <div class="alert alert-danger">
            <?php echo $error ?>
          </div>
        <?php elseif (isset($_GET['success']) && $_GET['success'] == 1): ?>
          <div class="alert alert-success">Your profile has been updated!</div>
        <?php endif ?>
        <form method="POST" action="profile.php">
          <div class="form-group">
            <label for="name">Name</label>
            <input readonly type="text" class="form-control" id="name" name="name" value="<?php echo $user['name'] ?>">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input readonly type="email" class="form-control" id="email" name="email"
              value="<?php echo $user['email'] ?>">
          </div>
          <!-- <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" class="form-control" id="password" name="password">
          </div> -->
          <!-- <button type="submit" class="btn btn-primary btn-block">Update Profile</button> -->
        </form>
        <!-- <a href="../logout.php">Logout</a> -->
      </div>
    </div>
    <div class="container-table" style="padding-top: 3vh">
      <table id="documents-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
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
        <tfoot>
          <tr>
            <th>ID</th>
            <th>Title</th>
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
        </tfoot>
      </table>
    </div>
  </div>
  <hr style='margin-top: 20px' class='container'>
  <h1 style='margin-top: 100px' class='container'>Similar Document</h1>
  <div class="container">
    <table id="similar-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
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


  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>



  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      var ajaxUrl = 'documents-user.php';
      var userId = "<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>";
      if (userId) {
        ajaxUrl = 'documents-user.php?id=' + userId;
      }
      var table = $('#documents-table').DataTable({
        "ajax": ajaxUrl,
        "columns": [
          { "data": "doc_id" },
          { "data": "title" },
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
            "data": null,
            "render": function (data, type, row) {
              return '<a class="btn btn-primary" href="edit-document.php?id=' + row.doc_id + '">Edit</a> <button class="btn btn-danger delete-btn" data-id="' + row.doc_id + '">Delete</button>';
            }
          }
        ],
        "initComplete": function () {
          this.api().columns().every(function () {
            var column = this;
            var select = $('<select><option value=""></option></select>')
              .appendTo($(column.footer()).empty())
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex(
                  $(this).val()
                );
                column
                  .search(val ? '^' + val + '$' : '', true, false)
                  .draw();
              });
            column.data().unique().sort().each(function (d, j) {
              select.append('<option value="' + d + '">' + d + '</option>')
            });
          });
        }
      });
      var table2 = $('#similar-table').DataTable({
        "ajax": "similar-documents.php",
        "columns": [
          { "data": "doc_id" },
          { "data": "title" },
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
            "data": null,
            "render": function (data, type, row) {
              return '<a class="btn btn-primary" href="edit-document.php?id=' + row.doc_id + '">Edit</a> <button class="btn btn-danger delete-btn" data-id="' + row.doc_id + '">Delete</button>';
            }
          }
        ]
      });

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
  </script>
</body>
</html>