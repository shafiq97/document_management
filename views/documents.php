<!DOCTYPE html>
<html>

<head>
  <title>Documents</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</head>
<style>
  /* Your styles here */

  .like-icon.favorited {
    color: red;
  }

  .like-icon.not-favorited {
    color: white;
  }

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
  $subjects = isset($_GET['subject']) ? $_GET['subject'] : [];


  // Define how many results you want per page
  $results_per_page = 5;

  // Find out the number of results stored in database
  $sql = "SELECT * FROM documents 
  INNER JOIN users ON documents.user_id = users.id 
  WHERE documents.title LIKE '%$searchQuery%' 
  AND documents.status <> 'draft'";

  if (count($subjects) > 0) {
    $subjectQuery = join("','", $subjects); // Create a comma-separated list of subjects
    $sql .= " AND documents.subject IN ('$subjectQuery')";
  }

  // die($sql);


  $result = mysqli_query($conn, $sql);
  $number_of_results = mysqli_num_rows($result);

  // Determine number of total pages available
  $number_of_pages = ceil($number_of_results / $results_per_page);

  // Determine which page number visitor is currently on
  if (!isset($_GET['page'])) {
    $page = 1;
  } else {
    $page = $_GET['page'];
  }

  // Determine the sql LIMIT starting number for the results on the displaying page
  $this_page_first_result = ($page - 1) * $results_per_page;
  $sql .= " LIMIT " . $this_page_first_result . ", " . $results_per_page;
  $result = mysqli_query($conn, $sql);
  $data = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  mysqli_close($conn);
  ?>


  <?php if (isset($alertClass)) : ?>
    <div class="<?php echo $alertClass; ?>"><?php echo $alertMessage; ?></div>
  <?php endif; ?>

  <div class="container" style="padding-top: 3vh">
    <!-- Search form -->
    <div class="col-12">

      <div class="row">
        <div class="col-12">
          <form action="" method="GET">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Search documents" name="query" value="<?php echo htmlspecialchars($searchQuery) ?>">
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
              <form action="" method="GET">
                <input type="hidden" name="query" value="<?php echo htmlspecialchars($searchQuery) ?>">
                <input type="checkbox" id="subject1" name="subject[]" value="Mathematics">
                <label for="subject1"> Mathematics</label><br>
                <input type="checkbox" id="subject2" name="subject[]" value="Science">
                <label for="subject2"> Science</label><br>
                <input type="checkbox" id="subject3" name="subject[]" value="Economics">
                <label for="subject3"> Economics</label><br>
                <input type="checkbox" id="subject4" name="subject[]" value="Computer Science">
                <label for="subject4"> Computer Science</label><br>
                <input type="checkbox" id="subject5" name="subject[]" value="Sociology">
                <label for="subject5"> Sociology</label><br>
                <input type="checkbox" id="subject6" name="subject[]" value="Accounting">
                <label for="subject6"> Accounting</label><br>
                <input type="checkbox" id="subject7" name="subject[]" value="History">
                <label for="subject7"> History</label><br>
                <input type="checkbox" id="subject8" name="subject[]" value="Art">
                <label for="subject8"> Art</label><br>
                <input type="checkbox" id="subject9" name="subject[]" value="Engineering">
                <label for="subject9"> Engineering</label><br>
                <input type="checkbox" id="subject10" name="subject[]" value="Business">
                <label for="subject10"> Business</label><br>
                <input type="checkbox" id="subject11" name="subject[]" value="English">
                <label for="subject11"> English</label><br>
                <input type="checkbox" id="subject12" name="subject[]" value="Others">
                <label for="subject12"> Others</label><br>
                <button class="btn btn-primary" type="submit">Filter</button>
                <button class="btn btn-warning" type="reset">Reset</button>
              </form>
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
              echo "<div class='card-text mb-2'>" . htmlspecialchars($document['subject']) . "</div>";
              echo "</div>";
              echo "</div>";
              echo "<div class='card-actions'>";
              echo "<a class='card-title btn btn-secondary mr-3' style='margin-left:58px' href='../api/" . $document['filepath'] . "' download='" . $document['filename'] . "'>Download</a>";
              echo "<a class='card-title btn btn-primary mr-3' href='../api/" . $document['filepath'] . "?preview=true'>Preview</a>";
              echo "<a class='card-title btn btn-warning mr-3' href='review.php?doc_id=" . $document['doc_id'] . "'>Review</a>";
              echo "<button class='card-title btn btn-outline-danger fav-button' data-doc-id='" . $document['doc_id'] . "' data-user-id='" . $_SESSION['user_id'] . "'>Favorite</button>";
              echo "</div>";
              echo "</div>";
            }
            ?>

          </div>
        </div>
      </div>
      <!-- display the links to the pages -->
      <!-- display the links to the pages -->
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <?php
          $url_query_params = http_build_query(array(
            'query' => $searchQuery,
            'subject' => $subjects
          ));
          for ($page = 1; $page <= $number_of_pages; $page++) { ?>
            <li class="page-item">
              <a class="page-link" href="documents.php?page=<?php echo $page; ?>&<?php echo $url_query_params; ?>"><?php echo $page; ?></a>
            </li>
          <?php } ?>
        </ul>
      </nav>

    </div>
    <script>
      document.querySelectorAll('.fav-button').forEach((button) => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          const docId = this.dataset.docId;
          const userId = this.dataset.userId; // Add logic to get the user ID here

          fetch('favorite.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `doc_id=${docId}&user_id=${userId}`
            })
            .then(response => response.json())
            .then(data => {
              if (data.favorited) {
                this.style.backgroundColor = 'red';
                this.style.color = 'white';
              } else {
                this.style.backgroundColor = 'white';
                this.style.color = 'red';
              }
            })
            .catch((error) => {
              console.error('Error:', error);
            });
        });
      });
    </script>


</body>

</html>