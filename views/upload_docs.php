<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>File Upload Form</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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