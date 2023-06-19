<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
	<title>File Upload Form</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

	<!-- Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body>
	<?php include('header.php') ?>
	<div class="container">
		<h1>File Upload Form</h1>
		<form action="../api/upload.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
			<div class="form-group">
				<label for="title">Document Title:</label>
				<input type="text" class="form-control" id="title" name="title">
			</div>
			<div class="form-group">
				<label for="date">Date:</label>
				<input type="date" class="form-control" id="date" name="date">
			</div>
			<div class="form-group">
				<label for="subject">Subject:</label>
				<select class="form-control" id="subject" name="subject">
					<option value="">Select a subject</option>
					<option value="Mathematics">Mathematics</option>
					<option value="Science">Science</option>
					<option value="Economics">Economics</option>
					<option value="Computer Science">Computer Science</option>
					<option value="Sociology">Sociology</option>
					<option value="Accounting">Accounting</option>
					<option value="History">History</option>
					<option value="Art">Art</option>
					<option value="Engineering">Engineering</option>
					<option value="Business">Business</option>
					<option value="English">English</option>
					<option value="Others">Others</option>
				</select>
			</div>
			<div class="form-group">
				<label for="description">Description:</label>
				<textarea class="form-control" id="description" name="description"></textarea>
			</div>
			<div class="form-group">
				<label for="type">Type of Document:</label>
				<input type="text" class="form-control" id="type" name="type">
			</div>
			<div class="form-group">
				<label for="author">Author:</label>
				<input type="text" class="form-control" id="author" name="author">
			</div>
			<div class="form-group">
				<label for="keywords">Annotation Keywords:</label>
				<input type="text" class="form-control" id="keywords" name="keywords">
			</div>
			<div class="form-group">
				<label for="status">Status:</label>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="status" id="draft" value="draft" checked>
					<label class="form-check-label" for="draft">
						Draft
					</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="status" id="publish" value="publish">
					<label class="form-check-label" for="publish">
						Publish
					</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="status" id="unpublish" value="unpublish">
					<label class="form-check-label" for="unpublish">
						Unpublish
					</label>
				</div>
			</div>
			<div class="form-group">
				<label for="file">Select file to upload:</label>
				<input type="file" class="form-control-file" id="file" name="file">
			</div>
			<button type="submit" class="btn btn-primary">Upload</button>
		</form>
	</div>
</body>

</html>