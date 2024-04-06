<?php
include "config.php"; 
include "check_token.php";  // Check user token

// Check user login or not
if(!isset($_SESSION['email'])){
   header('Location: auth-login.php');
}

?>
<!doctype html>
<html>
 <head></head>
 <body>
    <h1>Homepage</h1>
    <br>
    <a href="logout.php">Logout</a>
 </body>
</html>