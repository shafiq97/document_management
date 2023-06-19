<?php
// Your existing database connection code here
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(-1);
error_reporting(E_ALL);

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "document";
$conn       = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Retrieve favorite document IDs for the current user
$fav_doc_ids        = [];
$logged_in_user_id  = $_SESSION['user_id'];

$sql    = "SELECT doc_id FROM favorites WHERE user_id = $logged_in_user_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $fav_doc_ids[] = $row['doc_id'];
  }
}

// Fetch details for each favorite document
$fav_documents = [];
foreach ($fav_doc_ids as $doc_id) {
  $sql    = "SELECT * FROM documents WHERE doc_id = $doc_id";
  $result = mysqli_query($conn, $sql);
  if ($row = mysqli_fetch_assoc($result)) {
    $fav_documents[] = $row;
  }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-n0+8ZV9kGjXffk1C1tzbe8SDIY0wLrooYsS9FZzFdoeKqPhA9z07tZoCx0oIkNZR" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <!-- Custom Styles -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <link rel="apple-touch-icon" type="image/png" href="https://cpwebassets.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />

  <meta name="apple-mobile-web-app-title" content="CodePen">

  <link rel="shortcut icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />

  <link rel="mask-icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-b4b4269c16397ad2f0f7a01bcdf513a1994f4c94b8af2f191c09eb0d601762b1.svg" color="#111" />

  <title>Following</title>
  <link rel="canonical" href="https://codepen.io/chanonroy/pen/BLYOjp" />
  <style>
    /* VARIABLES ==================== */
    /* BASE ==================== */
    @import 'https://fonts.googleapis.com/css?family=Open+Sans';

    body {
      margin: 0;
      font-family: "Open Sans", sans-serif;
      background-color: #e0e0e0;
    }

    body ul {
      padding: 0;
      list-style-type: none;
    }

    img {
      width: 75px;
      margin: 7px 5px 5px 5px;
      border-radius: 5px;
    }

    button {
      border: none;
      font-size: 1em;
      transition: all 0.5s ease;
      cursor: pointer;
    }

    button:focus {
      outline: 0;
    }

    /* LAYOUT ==================== */
    .l-nav {
      display: flex;
      position: fixed;
      width: 100%;
      height: 100px;
      background-color: #242D33;
    }

    .l-nav__container,
    .l-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      align-items: center;
      width: 100%;
      max-width: 980px;
      margin: 0 auto;
    }

    @media screen and (max-width: 1050px) {

      .l-nav__container,
      .l-container {
        width: 95%;
      }
    }

    .l-nav__button {
      color: #e0e0e0;
      background-color: #3392CC;
      padding: 10px;
      text-align: center;
      width: 30%;
      border-radius: 5px;
    }

    .l-nav__button:hover {
      background-color: #2e83b8;
      color: white;
    }

    .l-container {
      display: block;
      padding-top: 115px;
    }

    /* MODULE ==================== */
    .list {
      display: flex;
      background-color: white;
      margin: 10px;
      padding: 5px;
      border-radius: 5px;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.2);
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .list:hover {
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.25), 0 5px 8px rgba(0, 0, 0, 0.22);
      cursor: pointer;
    }

    .list__profile {
      display: flex;
      flex-grow: 1;
      text-align: left;
      justify-content: flex-start;
    }

    .list__photos {
      display: flex;
      text-align: right;
      justify-content: flex-end;
    }

    .list__photos img {
      width: 100px;
    }

    @media screen and (max-width: 400px) {
      .list__photos img {
        width: 75px;
      }
    }

    .list__label {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 15px;
    }

    .list__label--header {
      color: #9a9a9a;
    }

    .list__label--value {
      font-size: 1.3em;
    }

    @media screen and (max-width: 650px) {
      .list {
        flex-direction: column;
      }

      .list__profile {
        justify-content: center;
        margin: 10px;
      }

      .list__label {
        width: 170px;
      }

      .list__photos {
        justify-content: center;
      }
    }

    /* STATE ==================== */
    .is-list-selected {
      background: #cb2d3e;
      /* fallback for old browsers */
      background: linear-gradient(to right, #cb2d3e, #ef473a);
      color: white;
    }
  </style>

  <script>
    window.console = window.console || function(t) {};
  </script>



</head>

<body translate="no">
  <?php include 'header.php' ?>
  <div class="l-container">
    <div class="text-center mb-5">
      <h1>Your Favorite Documents</h1>
    </div>
    <ul>
      <?php
      foreach ($fav_documents as $document) {
        echo '<a href="' . $document['filepath'] . '" download>';
        echo '<li class="list" data-name="' . $document['title'] . '">';
        echo '<div class="list__profile">';
        echo '<div class="list__label">';
        echo '<div class="list__label--header">Title</div>';
        echo '<div class="list__label--value">' . $document['title'] . '</div>';
        echo '</div>';
        echo '<div class="list__label">';
        echo '<div class="list__label--header">Author</div>';
        echo '<div class="list__label--value">' . $document['author'] . '</div>';
        echo '</div>';
        echo '</div>';
        echo '</li>';
        echo '</a>';
      }
      ?>
    </ul>
  </div>
  <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-2c7831bb44f98c1391d6a4ffda0e1fd302503391ca806e7fcc7b9b87197aec26.js"></script>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
  <script id="rendered-js">
    // Add state class for selected
    $(".list").click(function() {
      $(this).toggleClass("is-list-selected");
    });

    // Iterate through list and find selected
    $('.l-nav__button').click(function() {

      var marked = [];
      var not_marked = [];

      $('.list').each(function() {
        var has_selected = $(this).hasClass("is-list-selected");
        if (has_selected == true) {
          value = $(this).data("name");
          marked.push(value);
        } else {
          value = $(this).data("name");
          not_marked.push(value);
        }
      });

      console.log(marked);
      console.log(not_marked);

    });
    //# sourceURL=pen.js
  </script>


</body>

</html>