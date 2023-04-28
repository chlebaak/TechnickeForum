<?php

if (empty($_POST["firstname"])) {
    die("Firstname is required");
}

if (empty($_POST["lastname"])) {
    die("Lastname is required");
}

if (empty($_POST["username"])) {
    die("Username is required");
}

if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

if (empty($_POST["question"])) {
    echo $_POST["question"];
    die("Question is required");
}

if (empty($_POST["answear"])) {
    die("Answear is required");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO user (firstname, lastname, username, email, password_hash, question, answear, note)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("ssssssss",
                  $_POST["firstname"],
                  $_POST["lastname"],
                  $_POST["username"],
                  $_POST["email"],
                  $password_hash,
                  $_POST["question"],
                  $_POST["answear"],
                  $_POST["note"]);
                  
if ($stmt->execute()) {

    header("Location: signup-success.html");
    exit;
    
} else {
    
    if ($mysqli->errno === 1062) {
        die("email already taken");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}








