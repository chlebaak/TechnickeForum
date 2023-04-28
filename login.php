<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = sprintf("SELECT * FROM user
                    WHERE email = '%s'",
                   $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
    
    if ($user) {
        
        if (password_verify($_POST["password"], $user["password_hash"])) {
            
            session_start();
            
            session_regenerate_id();
            
            $_SESSION["user_id"] = $user["id"];
            
            header("Location: index.php");
            exit;
        }
    }
    
    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">

</head>
<style>

@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');

body {
    font-family: 'Roboto', sans-serif;
    background-color: #f2f2f2;
}
        /* Styl pro celý formulář */
form {

  max-width: 500px;
  margin: 0 auto;
  padding: 10px;
  background-color: white;

}

/* Styl pro nadpis */
h1 {
  font-size: 20px;
  font-weight: bold;
  text-align: center;
  margin-bottom: 1px;
}

/* Styl pro každý vstupní prvek */
div {
  margin-bottom: 5px;
}

label {
  display: block;
  font-weight: bold;
  padding: 5px;
}

input {
  width: 100%;
  padding: 6px 12px;
  margin: 4px 0;
  display: inline-block;
  border: none;
  background-color: #f2f2f2;
  border-left: 1px solid ;
  box-sizing: border-box;
  
}


/* Styl pro tlačítko */
button {
  background-color: #0096FF;
  color: white;
  padding: 12px 20px;
  margin-top: 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  width: 100%;
}

/* Styl pro tlačítko po najetí myší */
button:hover {
  background-color: deepskyblue;
}

div {
    position: relative;
    top: 150px;
}


    </style>
<body>
<h1>Not registered? <a href="signup.html">Signup</a></h1>
    
    <div>
    <form method="post">
    <h1>Login</h1>
    
    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>
        <label for="email">Email</label>
        <input type="email" name="email" id="email"
               value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <a href="FPemail.html">Forgot Password</a>
        <button>Log in</button>
    </form>
    </div>
    
</body>
</html>








