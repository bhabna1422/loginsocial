<?php
include("includes/connection.php");
session_start();
$cookie_name = 'a2zlogin';

    session_regenerate_id();
    unset($_SESSION['email']);
    unset($_SESSION['fname']);
    unset($_SESSION['user_id']);
    session_unset();
	
	if(isSet($_COOKIE[$cookie_name])){
		setcookie($cookie_name, '', time()-1000);
        setcookie($cookie_name, '', time()-1000, '/');
	}
	

print "<script>";
print "self.location='".SITEURL."';";
print "</script>";
?>
