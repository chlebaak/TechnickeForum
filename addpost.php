<?php
session_start();

$mysqli = require __DIR__ . "/database.php";
if (empty($_POST["nadpis"])) {
  echo "Bad input";
} else {
  $nadpis = test_input($_POST["nadpis"]);
}
if (empty($_POST["obsah"])) {
  echo "Bad input";
} else {
  $obsah = test_input($_POST["obsah"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
$id = $_SESSION["user_id"];

$sql = "INSERT INTO post (nadpis, obsah, date_time, userid)
        VALUES ('$nadpis', '$obsah', now(), '$id');";
        
        if ($mysqli->query($sql) === TRUE) {
          header("Location: index.php");
          exit;
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }

?>


