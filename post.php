<?php

use function PHPSTORM_META\sql_injection_subst;

session_start();

$mysqli = require __DIR__ . "/database.php";
if (isset($_SESSION["user_id"])) {
    
    
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}

?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');


      body {
  background-color: #f2f2f2;
  font-family: 'Roboto', sans-serif;
  margin: 0;
  padding: 0;
}

a {
  color: #0096FF;
}

p {
  font-size: 20px;
}

#wrapper {
  max-width: 50%;
  margin: 0 auto;
  padding: 20px;
  padding-top: 5px;
  background-color: white;
  border: 1px solid #A9A9A9;
}


#form {
  background-color: #ffffff;
  border: 1px solid #E5E4E2;
  margin: 20px;
  padding: 20px;
}

#form h1 {
  font-size: 24px;
  text-align: center;
}

#form label {
  display: block;
  font-size: 18px;
  margin-bottom: 2px;
}


#form textarea {
  display: block;
  width: 100%;
  font-size: 15px;
  padding: 10px;
  margin-bottom: 10px;
  height: 80px;
  border: 1px solid;
  border-radius: 4px;
  background-color: white;
}

#form form{
  margin: 0px;
}

#form button {
  display: block;
  width: 50%;
  padding: 5px;
  border: none;
  border-radius: 5px;
  background-color: #0096FF;
  color: #ffffff;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

#form button:hover {
  background-color: deepskyblue;
}

#delete {
  width: 15%;
  padding: 10px;
  margin: 10px;
  border: none;
  border-radius: 5px;
  background-color: #FFEA00		;
  color: black;
  font-size: 10px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

#delete:hover {
  background-color: #FDDA0D	;
}

.post {
  background-color: #ffffff;
  border: 1px solid #E5E4E2;
  margin-left: 20px;
  margin-right: 20px;
  margin-bottom: 10px;
  padding: 10px;
}

.post:hover {
  background-color: #f2f2f2;
}

.post h1 {
  font-size: 25px;
  margin-bottom: 10px;
}

.post p {
  font-size: 14px;
  margin-bottom: 10px;
}

.votes{
  display: inline;
}

#welcome {
  position: fixed;
  width: 20%;
  margin: 10px;
  padding: 10px;

}

 

.btn {
  width: 5%;
  padding: 10px;
  margin: 10px;
  border: none;
  border-radius: 5px;
  background-color: #FFA500;
  color: white;
  font-size: 12px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn:hover {
  background-color: #FFC000	;
}

    </style>
</head>
<body>
<div id="welcome" >
    <?php if (isset($user)): ?>
       <h1><a href="index.php">Main page</a></h1>

        <p>Hello <?= htmlspecialchars($user["username"]) ?></p>
        
        <p><a href="logout.php">Log out</a></p>
        
    <?php else: ?>
      <h1><a href="index.php">Main page</a></h1>
        <p><a href="login.php">Log in</a> or <a href="signup.html">sign up</a></p>
        
    <?php endif; ?>
    </div>
  <div id="wrapper">
    <h1 style="text-align: center;">Technické fórum</h1>
<?php
$conn = require __DIR__ . "/database.php";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$post_id = $_GET['id'];
$sql = "SELECT p.nadpis, p.obsah, p.date_time, u.username, p.id
FROM post AS p LEFT JOIN user AS u ON u.id = p.userid WHERE p.id = $post_id;";
$result = $conn->query($sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo 
        "<div id='". $row["id"]."' class='post'>
        <h1>"
        . $row["nadpis"]. 
        "</h1>
        <p>"
        . $row["obsah"].
        "</p>
        <i>Author: "
        . $row["username"].  
        "</i>
        <i>Date: "
        . $row["date_time"]. 
        "</i>
      </div>";
} else {
    echo "Post not found";
}
mysqli_close($conn);
?>

    <?php 
    if(isset($user)){
      echo 
      "<div id='form'>
      <form action='addComment.php?post_id=".$post_id."' method='POST'>
      <label for='comment'>Okomentuj</label>
      <textarea name='comment' id='comment' cols='30' rows='10'></textarea>
      <button>submit</button>
      </form>
    </div>";
    
    } else {
      echo "You need to be logged in order to comment... :)";
    }?>

    <div>
      <h1 style='font-size: 25px;'>Komentáře:</h1>
    </div>
    <?php
    

    $conn = require __DIR__ . "/database.php";
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT c.id,c.user_id, c.koment, v.votes, u.username FROM comment AS c LEFT JOIN user AS u ON u.id = c.user_id left JOIN votes AS v ON v.comment_id = c.id
    WHERE c.post_id = $post_id;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<div id='". $row["id"]."' class='post'>
        <p>"
        . $row["koment"].
        "</p>
        <i>Author: "
        . $row["username"].  
        "</i>
        <button class='btn'>+</button>
        <i class='votes'>". $row["votes"]."</i>
        <button class='btn'>-</button>";
        if(isset($user)){
          if($user["id"] == $row["user_id"]){
            echo "<form action='deleteComment.php?post_id=".$post_id."' method='POST'>
              <button name='delete' id='delete' value='". $row["id"]."'>Smazat</button>
              </form>";
          }
        }
      echo "</div>";
      }
    } else {
      echo "No comments... <br>";
    }

    $conn->close();
    ?>

  </div>
</body>
</html>
