<?php
session_start();
$post_id = $_GET['post_id'];
  if (empty($_POST["comment"])) {
    echo "Bad input";
  } else {
    $comment = test_input($_POST["comment"]);
  }


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if(isset($comment)){
  $conn = require __DIR__ . "/database.php";
  if (isset($_SESSION["user_id"])) {
    
    
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $sql = "INSERT INTO comment (koment, user_id, post_id)
    VALUES ('$comment', '$user[id]', '$post_id');";
    if (mysqli_query($conn, $sql)) {
      header("Location: post.php?id=$post_id");
          exit;
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
  mysqli_close($conn);
}


?>