<?php
session_start();
$user	= "root";   
	$password	= "";
	$dbname		= "login_register";
	$host		= "localhost";

$connect = mysqli_connect($host, $user, $password,$dbname);
// Check connection
if (!$connect) {
 die("Connection failed: " . mysqli_connect_error());
}