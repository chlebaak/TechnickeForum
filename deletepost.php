<?php
if(isset($_POST['delete'])) {
      $post_id = $_POST['delete'];

      $conn = require __DIR__ . "/database.php";
      
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $sql = "DELETE FROM post WHERE id=$post_id";
      if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
          exit;
      } else {
          echo "Chyba při mazání příspěvku: " . $conn->error;
      }
      $conn->close();
    }
?>