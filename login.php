<?php
include "config.php"; 

if(isset($_POST['submit'])){

 $email = mysqli_real_escape_string($connect,$_POST['email']);
 $password = mysqli_real_escape_string($connect,$_POST['password']);

 if ($email != "" && $password != ""){

  $sql_query = "select count(*) as cntUser from mng_members where email='".$email."' and pwd='".$password."'";
  $result = mysqli_query($connect,$sql_query);
  $row = mysqli_fetch_array($result);

  $count = $row['cntUser'];
echo $count;
  if($count == 0){
    $token = getToken(10);
    $_SESSION['email'] = $email;
    $_SESSION['token'] = $token;

    // Update user token 
    $result_token = mysqli_query($connect, "select count(*) as allcount from mng_members where email='".$email."' ");
    $row_token = mysqli_fetch_assoc($result_token);
    if($row_token['allcount'] > 0){
       mysqli_query($connect,"update mng_members set user_session_id='".$token."' where email='".$email."'");
       header('Location: index1.php');
    }else{
       mysqli_query($connect,"insert into mng_members(email,user_session_id	) values('".$email."','".$token."')");
       header('Location: index1.php');
    }
    header('Location: index1.php');
  }else{
    echo "Invalid email and password";
  }

 }

}

// Generate token
function getToken($length){
  $token = "";
  $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
  $codeAlphabet.= "0123456789";
  $max = strlen($codeAlphabet); // edited

  for ($i=0; $i < $length; $i++) {
    $token .= $codeAlphabet[random_int(0, $max-1)];
  }

  return $token;
}