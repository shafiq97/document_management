<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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

$docId = $_POST['doc_id'];
$userId = $_POST['user_id'];

// First, check if the document is already favorited
$checkSql = "SELECT * FROM favorites WHERE doc_id = $docId AND user_id = $userId";
$checkResult = mysqli_query($conn, $checkSql);

$response = [];

if (mysqli_num_rows($checkResult) > 0) {
  // The document is already favorited, so unfavorite it
  $deleteSql = "DELETE FROM favorites WHERE doc_id = $docId AND user_id = $userId";
  mysqli_query($conn, $deleteSql);
  $response["message"] = "Document unfavorited";
  $response["favorited"] = false;
} else {
  // The document is not favorited yet, so favorite it
  $insertSql = "INSERT INTO favorites (doc_id, user_id) VALUES ($docId, $userId)";
  mysqli_query($conn, $insertSql);
  $response["message"] = "Document favorited";
  $response["favorited"] = true;
}

header('Content-type: application/json');
echo json_encode($response);

mysqli_close($conn);
