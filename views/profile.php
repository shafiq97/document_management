<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
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
      justify-content: flex-start;
      margin-top: 50px;
    }

    .container {
      width: 100%;
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
        <form method="POST" action="profile.php" enctype="multipart/form-data">
          <div class="form-group" style="text-align: center;">
            <img height="150px" src="<?php echo $user['picture'] ?>" alt="Profile pic">
          </div>
          <div class="form-group">
            <label for="profile_picture">Profile Picture</label>
            <input type="file" class="form-control-file" id="profile_picture" name="profile_picture" accept="image/*">
          </div>
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name'] ?>">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email'] ?>">
          </div>
          <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password"
              placeholder="New Password">
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
              placeholder="Confirm New Password">
          </div>
          <div class="form-group">
            <label for="show_password">Show Password</label>
            <input type="checkbox" id="show_password">
          </div>
          <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
        </form>
      </div>
    </div>
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