<!DOCTYPE html>
<html>
<head>
  <title>Documents</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
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

</head>
<style>
  /* Your styles here */
  .card-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 10px;
  }

  .card-content {
    display: flex;
    align-items: start;
  }

  .filter-section {
    margin-bottom: 20px;
  }

  .card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
</style>
<body>
  <?php
  include('header.php');

  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);
  session_start();

  $servername = "localhost";
  $username   = "root";
  $password   = "";
  $dbname     = "document";

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $searchQuery = $_GET['query'] ?? '';

  $sql    = "SELECT * FROM documents 
             INNER JOIN users ON documents.user_id = users.id 
             WHERE documents.title LIKE '%$searchQuery%' and
             documents.status <> 'draft'";
  $result = mysqli_query($conn, $sql);

  $data = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  mysqli_close($conn);
  ?>

  <?php if (isset($alertClass)): ?>
    <div class="<?php echo $alertClass; ?>"><?php echo $alertMessage; ?></div>
  <?php endif; ?>

  <div class="container" style="padding-top: 3vh">
    <!-- Search form -->
    <div class="row">
      <div class="col-12">
        <form action="" method="GET">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search documents" name="query"
              value="<?php echo htmlspecialchars($searchQuery) ?>">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="row">
      <!-- Filter Section -->
      <div class="col-3 position-sticky" style="top:0;">
        <div class="filter-section">
          <h5>Filter by Subject:</h5>
          <!-- Your checkboxes here... -->
          <div>
            <input type="checkbox" id="subject1" name="subject1" value="Mathematics">
            <label for="subject1"> Mathematics</label><br>
            <input type="checkbox" id="subject2" name="subject2" value="Science">
            <label for="subject2"> Science</label><br>
            <input type="checkbox" id="subject3" name="subject3" value="Economics">
            <label for="subject3"> Economics</label><br>
            <input type="checkbox" id="subject4" name="subject4" value="Computer Science">
            <label for="subject4"> Computer Science</label><br>
            <input type="checkbox" id="subject5" name="subject5" value="Sociology">
            <label for="subject5"> Sociology</label><br>
            <input type="checkbox" id="subject6" name="subject6" value="Accounting">
            <label for="subject6"> Accounting</label><br>
            <input type="checkbox" id="subject7" name="subject7" value="History">
            <label for="subject7"> History</label><br>
            <input type="checkbox" id="subject8" name="subject8" value="Art">
            <label for="subject8"> Art</label><br>
            <input type="checkbox" id="subject9" name="subject9" value="Engineering">
            <label for="subject9"> Engineering</label><br>
            <input type="checkbox" id="subject10" name="subject10" value="Business">
            <label for="subject10"> Business</label><br>
            <input type="checkbox" id="subject11" name="subject11" value="English">
            <label for="subject11"> English</label><br>
            <input type="checkbox" id="subject12" name="subject12" value="Others">
            <label for="subject12"> Others</label><br>
          </div>
        </div>
      </div>

      <!-- Card List Section -->
      <div class="col-8">
        <div class="card-list">
          <?php
          foreach ($data as $document) {
            echo "<div class='card p-3'>";
            echo "<div class='card-content'>";
            echo "<a href='profile-user.php?id=" . htmlspecialchars($document['user_id']) . "'>";
            echo "<img class='card-img' src='" . htmlspecialchars($document['picture']) . "' alt='Profile Picture'>";
            echo "</a>";
            echo "<div>";
            echo "<div class='card-title'>" . htmlspecialchars($document['title']) . "</div>";
            echo "<div class='card-subtitle'>Author: " . htmlspecialchars($document['author']) . "</div>";
            echo "<div class='card-text mb-2'>" . htmlspecialchars($document['description']) . "</div>";
            echo "</div>";
            echo "</div>";
            echo "<div class='card-actions'>";
            echo "<a class='card-title btn btn-secondary mr-3' style='margin-left:58px' href='../api/" . $document['filepath'] . "' download='" . $document['filename'] . "'>Download</a>";
            echo "<a class='card-title btn btn-primary mr-3' href='../api/" . $document['filepath'] . "?preview=true'>Preview</a>";
            echo "<a class='card-title btn btn-warning' href='review.php?doc_id=" . $document['doc_id'] . "'>Review</a>";
            echo "</div>";
            echo "</div>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>