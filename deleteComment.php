<?php
if(isset($_POST['delete'])) {
  $comment_id = $_POST['delete'];
  $post_id = $_GET['post_id'];
  $conn = require __DIR__ . "/database.php";
  
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  $sql = "DELETE FROM comment WHERE id=$comment_id";
  if ($conn->query($sql) === TRUE) {
    header("Location: post.php?id=$post_id");
          exit;
  } else {
      echo "Chyba při mazání komentáře: " . $conn->error;
  }
  $conn->close();
}
?>