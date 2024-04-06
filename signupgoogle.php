<?php
include('includes/connection.php');

require_once('google/settings.php');
require_once('google/google-login-api.php');

error_reporting(1);

/* Login With Google */

if(isset($_GET['code'])) {
    
    // echo $_GET['code'];
    
	try {
		$gapi = new GoogleLoginApi();
		
		// Get the access token 
		$data = $gapi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL_GOOGLE, CLIENT_SECRET, $_GET['code']);
		
		// Get user information
		$user_info = $gapi->GetUserProfileInfo($data['access_token']);

// 		echo '<pre>';print_r($user_info); echo '</pre>';
		
// 		exit;
		
		$email  	= $user_info['emails'][0]['value'];
		$passcode	= $user_info['id'];
		$fullname 	= $user_info['displayName'];
		
		$fname  	= $user_info['name']['givenName'];
		$lname  	= $user_info['name']['familyName'];
		$gender		= $user_info['gender'];
		$profilepic	= $user_info['image']['url'];
		$loginwith	= "Google";
		
		/*Check if the user connected before */
		$sql = db_query("select * from `mng_members` where passcode='".$passcode."'");
		$numrow = mysqli_num_rows($sql);
		if($numrow > 0){
			$res = mysqli_fetch_object($sql);
			$_SESSION['email']    = $res->email;
			$_SESSION['fname']    = $res->fname;
			$_SESSION['user_id']  = $res->id;
			$_SESSION['passcode'] = $passcode;
			
			header('Location:user-dashboard');
			exit();
		}else{
			$date  = date("Y-m-d H:i:s");
			
			$mysql = "Insert into mng_members set fname='".$fname."', lname='".$lname."', email='".$email."', pwd='', passcode='".$passcode."', loginiwith='".$loginwith."', reg_date='".$date."', profilepic='".$profilepic."',user_status='1'";

		$query = db_query($mysql);
		$inserted_id = mysqli_insert_id($connect);
		
		
			$_SESSION['email']	  = $email;
			$_SESSION['fname']	  = $fname;
			$_SESSION['user_id']  = $inserted_id;
			$_SESSION['passcode'] = $passcode;
		
			header('Location:user-dashboard');
			exit();
		}
		
	}
	catch(Exception $e) {
		$error = $e->getMessage();
	//	header('Location:auth-signup?error='.$error);
		exit();
	}
}




?>