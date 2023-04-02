<!DOCTYPE html>
<html>
<head>
	<title>User Registration Form</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
		/* Center card on page */
		.container {
			display: flex;
			align-items: center;
			justify-content: center;
			height: 100vh;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="card" style="width: 30rem;">
			<div class="card-header">
				<h2>User Registration Form</h2>
			</div>
			<div class="card-body">
				<form method="post" action="api/register.php">
					<div class="form-group">
						<label for="username">Username:</label>
						<input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
					</div>
					<div class="form-group">
						<label for="password">Password:</label>
						<input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
					</div>
					<div class="form-group">
						<label for="email">Email address:</label>
						<input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
        <a href="login.php">Already registered? Login Here</a>
			</div>
		</div>
	</div>

	<!-- Bootstrap JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
