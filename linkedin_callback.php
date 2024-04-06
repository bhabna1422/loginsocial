<?php
error_reporting(E_ALL); // Error/Exception engine, always use E_ALL
ini_set('display_errors', TRUE); // Error/Exception display, use FALSE only in production
ini_set('log_errors', TRUE); // Error/Exception file logging engine.
ini_set('error_log', 'errors.log');
include('includes/connection.php');
function getAccessToken($code)
{
	require_once 'linkedin/vendor/autoload.php';
	include("linkedin/config.php");
	
	//$client = new GuzzleHttp\Client();
	
	$params = array (
		'form_params' => array(
			"grant_type" => "authorization_code",
			"code" => $code,
			"redirect_uri" => $CALLBACK_URL,
			"client_id" => $CLIENT_ID,
			"client_secret" => $CLIENT_SECRET
		),
		'headers' => array(
			"Content-Type" => "application/x-www-form-urlencoded"
		)
	);

	try {
		$client = new GuzzleHttp\Client([
			'base_uri' => 'https://www.linkedin.com'
		]);
		$response = $client->request('POST', '/oauth/v2/accessToken', $params);
		$data = json_decode($response->getBody()->getContents(), true);
		$output = array(
			"type" => "success",
			"access_token" => $data['access_token']
		);
	} catch (Exception $e) {
		$output = array(
			"type" => "error",
			"message" => $e->getMessage()
		);
	}
	return $output;
}


function getProfileData($accessToken)
{
	require_once 'linkedin/vendor/autoload.php';
	
	$params = array(
		'headers' => array(
			"Authorization" => "Bearer " . $accessToken
		)
	);
	
	try {
		$client = new GuzzleHttp\Client([
			'base_uri' => 'https://api.linkedin.com'
		]);
		$response = $client->request('GET', '/v2/me', $params);
		
		$url = 'https://api.linkedin.com/v2/me?projection=(profilePicture(displayImage~:playableStreams))&oauth2_access_token='.$access_token;
		$response1 = $client->request('GET', $url, $params);
		$data1 = json_decode($response1->getBody()->getContents(), true);
		$data = json_decode($response->getBody()->getContents(), true);
	
// 		echo "<pre>";
// 		print_r($data1);
// 		echo "</pre>";
// 		$profile = $data1['profilePicture']['displayImage~']['elements'][3]['identifiers'][0]['identifier'];
// 		echo $profile;
		

	
		$output = array(
			"type" => "success",
			"linkedin_profile_id" => $data['id'],
		
		
		);

      $linkedin_profile_id = $data['id'];
      $firstname = $data['localizedFirstName'];
      $lastname = $data['localizedLastName'];
      $profilePicture = $data1['profilePicture']['displayImage~']['elements'][3]['identifiers'][0]['identifier'];
      $vanityName = $data['vanityName'];
      $localizedHeadline = $data['localizedHeadline'];
      $loginwith	= "Linkedin";
      /*Check if the user connected before */
	$sql = db_query("select * from `mng_members` where passcode='".$linkedin_profile_id."'");
	$numrow = mysqli_num_rows($sql);
	if($numrow > 0){
		$res = mysqli_fetch_object($sql);

	    $_SESSION['email']    = $res->email;
		$_SESSION['fname']    = $res->fname;
		$_SESSION['user_id']  = $res->id;
		$_SESSION['passcode'] = $linkedin_profile_id;
       
		
		header('Location:user-dashboard.php');
		exit();
	}else{
	    $date  = date("Y-m-d H:i:s");
	
		
		$mysql = "Insert into mng_members set fname='".$firstname."', lname='".$lastname."', email='', pwd='', passcode='".$linkedin_profile_id."',
		loginiwith='".$loginwith."', reg_date='".$date."', profilepic='".$profilePicture."',user_status='1',description=''";

		$query = db_query($mysql);
		$inserted_id = mysqli_insert_id($connect);
		
		$_SESSION['email']	  = '';
		$_SESSION['fname']	  = $firstname;
		$_SESSION['user_id']  = $inserted_id;
		$_SESSION['passcode'] = $linkedin_profile_id;
	
		header('Location:user-dashboard.php');		
		exit();
	}

     
	} catch (Exception $e) {
		$output = array(
			"type" => "error",
			"message" => $e->getMessage()
		);
	}

// 	return $output;

}
	
	
session_start();
if (! empty($_GET["code"])) {
    $response 	  = getAccessToken($_GET["code"]);
	$access_token = $response["access_token"];
	
	$profile = getProfileData($access_token);


print_r($profile);
	

}
/*
$url = 'https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,emailAddress,profilePicture(displayImage~:playableStreams))&oauth2_access_token='.$access_token;
*/
?>
