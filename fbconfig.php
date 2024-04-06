<?php
include('includes/connection.php');
// added in v4.0.0
require_once 'autoload.php';
require_once 'Facebook/settings.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
// init app with app id and secret
//FacebookSession::setDefaultApplication( 'Your APP ID','Your APP Secret' );
FacebookSession::setDefaultApplication( App_ID,App_Secret);
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper(CLIENT_REDIRECT_URL);

try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}
// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response

  $graphObject 	= $response->getGraphObject();
  $fbid 		= $graphObject->getProperty('id');   // To Get Facebook ID
  $fbfullname 	= $graphObject->getProperty('name'); // To Get Facebook full name
  $femail 		= $graphObject->getProperty('email');// To Get Facebook email ID
  
	/* ---- Session Variables -----*/
	/*Check if the user connected before */
	$sql = db_query("select * from mng_members where passcode='".$fbid."'");
	$numrow = mysqli_num_rows($sql);
	if($numrow > 0){
		$res = mysqli_fetch_object($sql);
		$_SESSION['email']    = $res->email;
		$_SESSION['fname']    = $res->fname;
		$_SESSION['user_id']  = $res->id;
		$_SESSION['passcode'] = $fbid;
		
		header('Location:user-dashboard');
		exit();
	}else{
		$date  = date("Y-m-d H:i:s");
		$name = explode(' ',$fbfullname);
		
		$mysql = "Insert into mng_members set fname='".$name[0]."', lname='".$name[1]."', email='".$femail."', pwd='', passcode='".$fbid."', loginiwith='Facebook', reg_date='".$date."', user_status='1'";

		$query = db_query($mysql);
		$inserted_id = mysqli_insert_id($connect);
		
		$_SESSION['email']	  = $femail;
		$_SESSION['fname']	  = $name[0];
		$_SESSION['user_id']  = $inserted_id;
		$_SESSION['passcode'] = $fbid;
		
		header('Location:user-dashboard');
	}
} else {
  $loginUrl = $helper->getLoginUrl();
  header("Location:auth-signup?error");
}

?>