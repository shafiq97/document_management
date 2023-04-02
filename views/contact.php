<?php
// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $name    = $_POST['name'];
  $email   = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  // Validate form data
  $errors = array();
  if (empty($name)) {
    $errors[] = "Name is required";
  }
  if (empty($email)) {
    $errors[] = "Email is required";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
  }
  if (empty($subject)) {
    $errors[] = "Subject is required";
  }
  if (empty($message)) {
    $errors[] = "Message is required";
  }

  // If no errors, send email
  if (empty($errors)) {
    // Set up email headers and content
    $to      = "youremail@example.com";
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $subject = "New Contact Form Submission: $subject";
    $body    = "<h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong> $message</p>";

    // Send email
    if (mail($to, $subject, $body, $headers)) {
      $success_msg = "Your message has been sent successfully.";
    } else {
      $error_msg = "There was a problem sending your message. Please try again later.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Contact Us</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <?php include('header.php') ?>
  <div class="container">
    <h1>Contact Us</h1>
    <?php if (isset($success_msg)) { ?>
      <div class="alert alert-success" role="alert">
        <?php echo $success_msg; ?>
      </div>
    <?php } elseif (isset($error_msg)) { ?>
      <div class="alert alert-danger" role="alert">
        <?php echo $error_msg; ?>
      </div>
    <?php } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name"
          value="<?php if (isset($_POST['name']))
            echo htmlspecialchars($_POST['name']); ?>">
        <?php if (isset($errors) && in_array("Name is required", $errors)) { ?>
          <div class="alert alert-danger" role="alert">
            Name is required
          </div>
        <?php } ?>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email"
          value="<?php if (isset($_POST['email']))
            echo htmlspecialchars($_POST['email']); ?>">
        <?php if (isset($errors) && in_array("Email is required", $errors)) { ?>
          <div class="alert alert-danger" role="alert">
            Email is required
          </div>
        <?php } elseif (isset($errors) && in_array("Invalid email format", $errors)) { ?>
          <div class="alert alert-danger" role="alert">
            Invalid email format
          </div>
        <?php } ?>
      </div>
      <div class="form-group">
        <label for="subject">Subject:</label>
        <input type="text" class="form-control" id="subject" name="subject"
          value="<?php if (isset($_POST['subject']))
            echo htmlspecialchars($_POST['subject']); ?>">
        <?php if (isset($errors) && in_array("Subject is required", $errors)) { ?>
          <div class="alert alert-danger" role="alert">
            Subject is required
          </div>
        <?php } ?>
      </div>
      <div class="form-group">
        <label for="message">Message:</label>
        <textarea class="form-control" id="message"
          name="message"><?php if (isset($_POST['message']))
            echo htmlspecialchars($_POST['message']); ?></textarea>
        <?php if (isset($errors) && in_array("Message is required", $errors)) { ?>
          <div class="alert alert-danger" role="alert">
            Message is required
          </div>
        <?php } ?>
      </div>
      <button type="submit" class="btn btn-primary">Send</button>
    </form>
  </div>
</body>
</html>