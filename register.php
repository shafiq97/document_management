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
			overflow-y: scroll;
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
				<form method="post" action="api/register.php" id="registrationForm" enctype="multipart/form-data">
					<div class="form-group">
						<label for="username">Username:</label>
						<input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
					</div>
					<div class="form-group">
						<label for="password">Password:</label>
						<input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
					</div>
					<div class="form-group">
						<label for="email">Email address:</label>
						<input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
					</div>
					<div class="form-group">
						<label for="name">Name:</label>
						<input type="name" class="form-control" id="name" name="name" placeholder="Enter your name">
					</div>
					<div class="form-group">
						<label for="role">Role</label>
						<select class="form-control" name="role" id="role">
							<option value="">Select role</option>
							<option value="student">Student</option>
							<option value="lecturer">Lecturer</option>
						</select>
					</div>
					<div class="form-group">
						<label for="course">Course:</label>
						<input type="course" class="form-control" id="course" name="course" placeholder="Enter course">
					</div>
					<div class="form-group">
						<label for="programme">Programme:</label>
						<input type="programme" class="form-control" id="programme" name="programme" placeholder="Enter programme">
					</div>
					<div class="form-group">
						<label for="photo">Photo:</label>
						<input type="file" class="form-control-file" id="photo" name="photo">
						<img height="300px" id="preview" style="display: none">
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
	<script>
		const form = document.querySelector('#registrationForm');
		const passwordInput = document.querySelector('#password');
		const roleDropdown = document.querySelector('#role');
		const courseInput = document.querySelector('#course');
		const programmeInput = document.querySelector('#programme');

		// hide the course and programme inputs by default
		courseInput.style.display = 'none';
		programmeInput.style.display = 'none';

		// add an event listener to the role dropdown
		roleDropdown.addEventListener('change', function (event) {
			const selectedRole = event.target.value;

			// show or hide the course and programme inputs based on the selected role
			if (selectedRole === 'lecturer') {
				courseInput.style.display = 'none';
				programmeInput.style.display = 'none';
			} else {
				courseInput.style.display = 'block';
				programmeInput.style.display = 'block';
			}
		});

		form.addEventListener('submit', function (event) {
			const passwordValue = passwordInput.value;
			if (passwordValue.length < 8) {
				alert('Password should be at least 8 characters long');
				event.preventDefault();
			} else if (!/[A-Z]/.test(passwordValue)) {
				alert('Password should contain at least one uppercase letter');
				event.preventDefault();
			}
			// add other password validation criteria here
		});

		const photoInput = document.querySelector('#photo');
		const previewImg = document.querySelector('#preview');

		photoInput.addEventListener('change', function (event) {
			const file = event.target.files[0];
			const reader = new FileReader();

			reader.addEventListener('load', function (event) {
				previewImg.src = event.target.result;
				previewImg.style.display = 'block';
			});

			reader.readAsDataURL(file);
		});

	</script>




</body>
</html>