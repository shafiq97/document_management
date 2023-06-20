<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

// Set up database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "document";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function search_keywords($table, $keywords) {
  $keyword_array = explode(' ', $keywords);
  $conditions = array();
  foreach ($keyword_array as $keyword) {
    if(!empty($keyword)){
      $conditions[] = "`keywords` LIKE '%" . $keyword . "%'";
    }
  }
  $query = "SELECT * FROM `" . $table . "` WHERE " . implode(' OR ', $conditions);
  return $query;
}


// Retrieve documents data from database
if (isset($_GET['id'])) {
  $sql = "SELECT * FROM documents where user_id = '$_GET[id]' and status <> 'draft'";
} else {
  $sql = "SELECT * FROM documents where user_id = '$_SESSION[user_id]'";
}
$result = mysqli_query($conn, $sql);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Output documents data in JSON format
// echo json_encode(array("data" => $data));

$json_data = json_encode(array("data" => $data));

$data = json_decode($json_data, true);

$keywords_string = '';
foreach ($data['data'] as $document) {
  $keywords = explode(',', $document['keywords']);
  foreach ($keywords as $keyword) {
    $keywords_string .= $keyword . ' ';
  }
}

$table = 'documents';
$keywords = trim($keywords_string);
$query = search_keywords($table, $keywords);
$result = mysqli_query($conn, $query);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
echo json_encode(array("data" => $data));

// echo $query;

// Close database connection
mysqli_close($conn);
?>
