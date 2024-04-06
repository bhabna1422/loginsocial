<?php 
header('X-Frame-Options: SAMEORIGIN');
header("X-XSS-Protection: 1; mode=block");
header('X-Content-Type-Options: nosniff');
include("includes/connection.php");

$email 		= $connect->real_escape_string(filter_inputs(base64_decode($_GET['id'])));
$query		= db_query("select * from mng_members where email='".$email."' and user_status ='0'");
$resuser 	= mysqli_num_rows($query);
$row		= mysqli_fetch_array($query);


if($resuser == 0){
	header('location:'.SITEURL.'?status='.base64_encode('error'));
	exit;
}else{
	$query		= db_query("update mng_members set user_status ='1' where email='".$email."'");
	$msg1 = base64_encode('Your Account Activated Successfully.');
	setcookie("msg", $msg1, time() + 3);
	header("location:auth-login");
	exit();
}
?>
