<?php
session_start();
$_SESSION['loginwith']= "Twitter";
include ('twitter/config.php');
require "twitter/vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$api_key		= "1Y2NRidyu9jSPytpAjjf84Evt"; 
$api_key_secret = "xgDIki5cUm815p8Oxews2HosdznJF0CrXXhHjqhX0UIsjaT0CAN";

$access_token 		 = "1606498010-m5dnQ49689it8MJmco11GUt0KL0fTJjCxDpBlM8";
$access_token_secret = "mVaM12WF2a6q9PqFyVBKEW6OD8m74EFOcKyuQFmEZxcoC";


define('CONSUMER_KEY', getenv('SkVON1dEWUZZc0lQT3R4ZTZhQTA6MTpjaQ'));
define('CONSUMER_SECRET', getenv('N5P6rSigfPRqVKQPJvl2ZYduPz_2dS4vDe0ssORETILoMxyM0n'));
define('OAUTH_CALLBACK', getenv('https://www.a2zwebhelp.com/login_register/signuptwitter.php'));

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

$content = $connection->get("account/verify_credentials");


$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

print_r($request_token);
exit;


https://twitter.com/i/oauth2/authorize?response_type=code&client_id=SkVON1dEWUZZc0lQT3R4ZTZhQTA6MTpjaQ&redirect_uri=https://www.a2zwebhelp.com/login_register/signuptwitter.php&scope=tweet.read users.read like.write tweet.write offline.access&state=12321321&code_challenge=23423432432&code_challenge_method=plain

/*
https://twitter.com/i/oauth2/authorize?response_type=code&client_id=SkVON1dEWUZZc0lQT3R4ZTZhQTA6MTpjaQ&redirect_uri=https://www.a2zwebhelp.com/login_register/signuptwitter.php&scope=tweet.read%20users.read%20offline.access&state=state&code_challenge=challenge&code_challenge_method=plain

*/


$request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => "https://www.a2zwebhelp.com/login_register/signuptwitter.php"));

$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

$url = $connection->url("oauth/authorize", array("oauth_token" => $request_token['oauth_token']));
header('Location: ' . $url);


?>