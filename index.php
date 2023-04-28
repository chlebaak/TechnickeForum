<?php

session_start();

$mysqli = require __DIR__ . "/database.php";
if (isset($_SESSION["user_id"])) {
    
    
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Technické fórum</title>
    <meta charset="UTF-8">
    <script
  src="https://code.jquery.com/jquery-3.6.3.min.js"
  integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
  crossorigin="anonymous"></script>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');


      body {
  background-color: #f2f2f2;
  font-family: 'Roboto', sans-serif;
  margin: 0;
  padding: 0;
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
  margin-bottom: 10px;
  text-align: center;
}

#form label {
  display: block;
  font-size: 18px;
  margin-bottom: 2px;
}

#form input{
  display: block;
  width: 95%;
  padding: 10px;
  margin-bottom: 10px;
  height: 20px;
  border: none;
  border-left: 1px solid;
  background-color: #f2f2f2;
}
#form textarea {
  display: block;
  width: 95%;
  padding: 10px;
  margin-bottom: 10px;
  height: 60px;
  border: none;
  border-left: 1px solid;
  background-color: #f2f2f2;
}

#form button {
  display: block;
  width: 100%;
  padding: 10px;
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

.btn {
  width: 30%;
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
  background-color: #FF7518	;
}

#welcome a{
  color: #0096FF;
}

#wrapper p {
  font-size: 18px;
}

#wrapper i {
  font-size: 13px;
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
  font-size: 20px;
  margin-bottom: 10px;
}

.post p {
  font-size: 14px;
  margin-bottom: 10px;
}

#welcome {
  position: fixed;
  width: 20%;
  margin: 10px;
  padding: 10px;

}

a {
  color: #0096FF;
}

    </style>
</head>

<body>
    
    
    <div id="welcome" >
    <?php if (isset($user)): ?>
        
        <p>You are logged as: <?= htmlspecialchars($user["username"]) ?></p>
        
        <p><a href="logout.php">Log out</a></p>
        
    <?php else: ?>
        
        <p><a href="login.php">Log in</a> or <a href="signup.html">sign up</a></p>
        
    <?php endif; ?>
    </div>
    <div id="wrapper">
    <h1 style="text-align: center;">Technické fórum</h1>
    <?php 
    if(isset($user)){
      echo "<div id='form'>
      <form action='addpost.php' method='POST'>
          <label for='nadpis'>Nadpis</label>
          <input type='text' id='nadpis' name='nadpis'>
          <label for='obsah'>Obsah</label>
          <textarea name='obsah' id='obsah' cols='30' rows='10'></textarea>
          <button>post</button>
      </form>
  </div>";
    } else {
      echo "You need to be logged in in order to post... :)";
    }
    
    ?>

    <div>
      <form action="" method="GET">
      <button class="btn" name="date" id="date">By date</button>
      <button class="btn" name="alphabet" id="alphabet">By alphabet</button>
      <button class="btn" name="comments" id="comments">By number of comments</button>
      </form>
    </div>

    <?php
    $orderBy = "";
    if ($_SERVER["REQUEST_METHOD"] == "GET"){
      if(isset($_GET["alphabet"])){
        $orderBy = "ORDER BY p.nadpis";
      }
      if(isset($_GET["date"])){
        $orderBy = "ORDER BY p.date_time";
      }
      if(isset($_GET["comments"])){
        $orderBy = "ORDER BY COUNT(c.koment) DESC";
      }
    }
    $sql2 = "SELECT p.nadpis, p.obsah, p.date_time, u.username, p.id, p.userid, COUNT(c.koment)
    FROM post AS p LEFT JOIN user AS u ON u.id = p.userid LEFT JOIN comment AS c ON p.id = c.post_id GROUP BY p.id $orderBy;";
    $result = $mysqli->query($sql2);
    
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo 
        "<div id='". $row["id"]."' class='post'>
        <h1>"
        . $row["nadpis"]. 
        "</h1>
        <p>"
        . $row["obsah"].
        "</p>
        <i>Date: "
        . $row["date_time"].  
        "</i><br>
        <i>Author: "
        . $row["username"]. 
        "</i><br>
        <i>Počet komentářů: "
        . $row["COUNT(c.koment)"]. 
        "</i>";
        if(isset($user)){
          if($user["id"] == $row["userid"]){
            echo "<form action='deletepost.php' method='POST'>
            <button name='delete' id='delete' value='". $row["id"]."'>Smazat</button>
            </form>";
          }
        }

        echo "</div>";
        }
        
    } else {
      echo "No posts...";
    }


    ?>
    </div>

    
    <script>
      $(document).ready(function() {
   $(".post").click(function() {
      var post_id = $(this).attr("id");
      window.location.href = "post.php?id=" + post_id;
   });
});

    </script>
    
    
</body>
</html>
    
    
    
    
    
    
    
    
    
    
    
