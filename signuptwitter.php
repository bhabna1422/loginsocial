<?php

include('includes/connection.php');
include ('twitter/config.php');
require "twitter/vendor/autoload.php";
error_reporting(1);
//$btnstatus = "disabled";

$state 		= $_SESSION['state'];
$challenge 	= "A2ZWebhelp";


    if ( array_key_exists('code', $_GET) ) {
        $client = new GuzzleHttp\Client([
            'base_uri' => 'https://api.twitter.com',
        ]);

        try {
            // get access token
            $response = $client->request('POST', '/2/oauth2/token', [
                "form_params" => [
                    "grant_type" => "authorization_code",
                    "code" => $_GET['code'],
                    "client_id" => CLIENT_ID,
                    "redirect_uri" => REDIRECT_URL,
                    "code_verifier" => $challenge,
                ],
            ]);
        
            $res = json_decode($response->getBody());
			//print_r($res);
			$access_token = $res->access_token;
			$refresh_token = $res->refresh_token;
			//echo $access_token;
			get_user_details($access_token, $refresh_token);
			
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
 else {
   // echo "Something went wrong. Try again later.";
   header('Location:auth-signup?error');
}


function get_user_details($access_token) {
    //echo $access_token;
try {
        // get user details
        $client = new GuzzleHttp\Client([
            'base_uri' => 'https://api.twitter.com',
        ]);

        $response = $client->request('GET', '/2/users/me', [
            "headers" => [
                "Authorization" => "Bearer ". $access_token
            ]
        ]);
		$body = $response->getBody()->getContents();
		$base64 = base64_encode($body);
		$mime = "image/jpeg";
		
		$res = json_decode($response->getBody());
		
		$passcode 	= $res->data->id;
		$fullname 	= $res->data->name;
		$description = $res->data->description;
		$profilepic	= ('data:' . $mime . ';base64,' . $base64);
		$username 	= $res->data->username;
		$loginwith	= "Twitter";
		$public_metrics = $res->data->public_metrics;
		$propic = $res->data->verified;
// 			$username  = $res->data->username;
		
	
		
		
		
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
		    echo $description, $passcode ,	$propic,$username;
		   
// 		$date  = date("Y-m-d H:i:s");
// 		$name = explode(' ',$fullname);
	
// 		$mysql = "Insert into mng_members set fname='".$name[0]."', lname='".$name[1]."', email='', pwd='', passcode='".$passcode."', loginiwith='".$loginwith."', reg_date='".$date."', profilepic='".$profilepic."',user_status='1',description='".$description."',public_metrics='".$public_metrics."'";

// 		$query = db_query($mysql);
// 		$inserted_id = mysqli_insert_id($connect);
		
// 		$_SESSION['email']	  = '';
// 		$_SESSION['fname']	  = $name[0];
// 		$_SESSION['user_id']  = $inserted_id;
// 		$_SESSION['passcode'] = $passcode;
	
// 		header('Location:user-dashboard');		
// 		exit();
	}
		
    } catch(Exception $e) {
        if (401 == $e->getCode()) {
            

            $response = $client->request('POST', '/2/oauth2/token', [
                'form_params' => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refresh_token,
                    "client_id" => CLIENT_ID,
                ],
            ]);

            $db->update_access_token($response->getBody());
            
            get_user_details();
        }
    }
}
exit;
?>