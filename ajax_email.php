<?php 
include("includes/connection.php");
if(isset($_POST['email'])):
	extract($_POST);
	$email = $_POST['email'];
  	$sql = db_query("select id from mng_members where email='$email'");
  	if(mysqli_num_rows($sql))
    {
		echo 'Already Exists';
    }
  else
    {
        echo 'OK';
    }
 endif;

?>