<!DOCTYPE html>
<html>
<head>
  <title>Login - My Website</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <!-- Custom Styles -->
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f5f5f5;
    }

    .container {
      margin-top: 50px;
      max-width: 450px;
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
      transition: all 0.2s ease-in-out;
    }

    .btn:hover {
      background-color: #357ae8;
      border-color: #357ae8;
      transform: scale(1.05);
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <?php
      if (isset($_GET['message'])) {
        ?>
        <div class="alert alert-success">
          <p>User has been registered successfully</p>
        </div>
        <?php
      }
      ?>
      <h1 class="card-title">Login</h1>
      <?php if (isset($_GET['error']) && $_GET['error'] == 1) { ?>
        <div class="alert alert-danger" role="alert">
          Incorrect username or password. Please try again.
        </div>
      <?php } ?>
      <form method="POST" action="api/login_check.php">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group position-relative">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
          <span class="password-toggle" onclick="togglePasswordView()">
            <i class="fas fa-eye" id="toggle-password-icon"></i>

          </span>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
      </form>
      <a href="register.php">Not registered yet? Register Here</a>
      <a href="admin/login.php">Login as admin</a>
    </div>
  </div>
  <!-- FontAwesome -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.jsintegrity="
    sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
  <script>
    // Use JavaScript to remove the alert message after a few seconds
    setTimeout(function () {
      document.querySelector('.alert').remove();
    }, 5000);
    function togglePasswordView() {
      const passwordInput = document.getElementById('password');
      const passwordIcon = document.getElementById('toggle-password-icon');

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
      }
    }
  </script>
</body>
</html>