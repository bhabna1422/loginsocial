<?php
include('includes/connection.php');
//error_reporting(1);

/* FaceBook */
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

$loginwith = $connect->real_escape_string($_GET['sm']);	
	
/* 1. Register/ Login with Facebook */
if($loginwith=="Facebook"){
	$_SESSION['loginwith']= "Facebook";
	FacebookSession::setDefaultApplication(App_ID,App_Secret);
	$helper = new FacebookRedirectLoginHelper(CLIENT_REDIRECT_URL);
	$loginUrl = $helper->getLoginUrl();
	header('Location:'.$loginUrl);
	exit();

}
/* Clear all session value */
$_SESSION['pcode']		="";
$_SESSION['email']		="";
$_SESSION['fname']		="";
$_SESSION['user_id']	="";
$_SESSION['loginwith']	="";


/* ========================================================================================= */
/* 2. Register/ Login with Google */
if($loginwith=="Google"){
	$_SESSION['loginwith']= "Google";
	require_once('google/settings.php');
	require_once('google/google-login-api.php');
	$loginUrl = 'https://accounts.google.com/o/oauth2/auth?scope=https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email&redirect_uri='.urlencode(CLIENT_REDIRECT_URL_GOOGLE).'&response_type=code&client_id='.CLIENT_ID.'&access_type=online';
	
	header('Location:'.$loginUrl);
	exit();
}


/* ========================================================================================= */
/* 3. Register/ Login with Twitter */
if($loginwith=="Twitter"){
	$_SESSION['loginwith']= "Twitter";
	include ('twitter/config.php');
	$scope = "tweet.read users.read like.write tweet.write offline.access";
	$state = $_SESSION['state'] = rand(999,999999);
	$code_challenge = "A2ZWebhelp";
	
	$loginUrl = 'https://twitter.com/i/oauth2/authorize?response_type=code&client_id='.CLIENT_ID.'&redirect_uri='.REDIRECT_URL.'&scope='.$scope.'&state='.$state.'&code_challenge='.$code_challenge.'&code_challenge_method=plain';
	
	header('Location:'.$loginUrl);
	exit();
}


/* ========================================================================================= */
/* 3. Register/ Login with linkedin */
if($loginwith=="Linkedin"){
	$_SESSION['loginwith']= "Linkedin";
	include ('linkedin/config.php');
	
	$state = $_SESSION['state'] = rand(999,999999);
	$code_challenge = "A2ZWebhelp";
	
// 	$loginUrl = 'https://twitter.com/i/oauth2/authorize?response_type=code&client_id='.CLIENT_ID.'&redirect_uri='.REDIRECT_URL.'&scope='.$scope.'&state='.$state.'&code_challenge='.$code_challenge.'&code_challenge_method=plain';
	$redirectUrl = $authUrl."?response_type=code&client_id=".$CLIENT_ID."&redirect_uri=". $CALLBACK_URL."&scope=".$SCOPE;
	echo $redirectUrl;
    header("Location:".$redirectUrl);
    exit();
}



?>