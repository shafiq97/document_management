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
$user_id = $_SESSION['user_id'];
$sql     = "SELECT * FROM users WHERE id = $user_id";
$result  = mysqli_query($conn, $sql);
$user    = mysqli_fetch_assoc($result);

// Update user information if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name     = mysqli_real_escape_string($conn, $_POST['name']);
  $email    = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['new_password']);

  $profile_picture = "";
  if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $upload_dir = '../uploads/profile_pictures/';
    $file_ext   = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $file_name  = $user_id . '.' . $file_ext;
    $file_path  = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $file_path)) {
      $profile_picture = "profile_picture = '$file_name',";
    } else {
      $error = "There was an error uploading your profile picture.";
    }
  }

  $error            = "";
  $new_password     = trim($_POST['new_password']);
  $confirm_password = trim($_POST['confirm_password']);

  if (!empty($new_password) && !empty($confirm_password)) {
    if ($new_password !== $confirm_password) {
      $error = "New password and confirm password do not match.";
    }
  }

  if (empty($error)) {
    if (!empty($new_password)) {
      $password = password_hash($new_password, PASSWORD_DEFAULT);
      $sql      = "UPDATE users SET name = '$name', picture = '$file_path', email = '$email', password = '$password' WHERE id = $user_id";
    } else {
      $sql = "UPDATE users SET name = '$name', picture = '$file_path', email = '$email' WHERE id = $user_id";
    }

    if (mysqli_query($conn, $sql)) {
      $_SESSION['name'] = $name;
      header("Location: profile.php?success=1");
      exit();
    } else {
      $error = "There was an error updating your profile.";
    }
  }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Profile</title>
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
      justify-content: center;
      align-items: center;
    }

    .container-table {
      width: calc(100%);
      margin-left: 10px;
      margin-left: 20px;
    }

    .container {
      width: 100%;
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

    .btn-primary,
    .btn-danger {
      border-radius: 20px;
      font-weight: bold;
      transition: all 0.2s ease-in-out
    }

    .btn-primary {
      background-color: #4285f4;
      border-color: #4285f4;
    }

    .btn-primary:hover {
      background-color: #357ae8;
      border-color: #357ae8;
      transform: scale(1.05);
    }

    .btn-danger {
      background-color: #dc3545;
      border-color: #dc3545;
    }

    .btn-danger:hover {
      background-color: #c82333;
      border-color: #c82333;
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
      </table>
    </div>
  </div>
  <!-- <div class="container">
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
  </div> -->


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
      var table = $('#documents-table').DataTable({
        "ajax": "documents-user.php",
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

          // {
          //   "data": null,
          //   "render": function (data, type, row) {
          //     return '<a class="btn btn-primary" href="edit-document.php?id=' + row.id + '">Edit</a> <button class="btn btn-danger delete-btn" data-id="' + row.id + '">Delete</button>';
          //   }
          // }
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
    function togglePasswordVisibility(inputElement, show) {
      if (show) {
        inputElement.attr('type', 'text');
      } else {
        inputElement.attr('type', 'password');
      }
    };
    $(document).ready(function () {
      $('#show_password').on('change', function () {
        var show = $(this).is(':checked');
        togglePasswordVisibility($('#new_password'), show);
        togglePasswordVisibility($('#confirm_password'), show);
      });
    });

  </script>
</body>
</html>