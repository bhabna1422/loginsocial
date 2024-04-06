<?php

if (isset($_SESSION['email'])) {
  $result = mysqli_query($connect, "SELECT user_session_id	 FROM mng_members where email='".$_SESSION['email']."'");
 
  if (mysqli_num_rows($result) > 0) {
 
    $row = mysqli_fetch_array($result); 
    $token = $row['token']; 

    if($_SESSION['token'] != $token){
      session_destroy();
      header('Location: auth-login.php');
    }
  }
}