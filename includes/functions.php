<?php


// ---------- Cookie Info ---------- //
$cookie_name = 'a2zlogin';
$cookie_time = (3600 * 24 * 30); // 30 days

//------------- AUTO LOGIN ----------------//

if(isSet($cookie_name)){
// Check if the cookie exists
	if(isSet($_COOKIE[$cookie_name])){
		parse_str($_COOKIE[$cookie_name]);

		$_SESSION['email']		= $email;
		$_SESSION['uname'] 		= $uname;
		$_SESSION['user_id']	= $user_id;
		
		//header("location:$fullurl");
		//exit;
	}
}

function removeSpchar($str){
    $str = str_replace("%", "-", $str);
    $str = str_replace("#", "-", $str);
    $str = str_replace("!", "-", $str);
    $str = str_replace("@", "-", $str);
    $str = str_replace("^", "-", $str);
    $str = str_replace("*", "-", $str);
    $str = preg_replace('/\s\&+/', '-', $str);
    $str = preg_replace("/\s/", "-", $str);
    $str = preg_replace('/\-\-+/', '-', $str);
    $str = str_replace("(", "-", $str);
    $str = str_replace(")", "-", $str);
    $str = str_replace("(", "-", $str);
    $str = str_replace(")", "_", $str);
    $str = str_replace("_", "-", $str);
    $str = str_replace("&", "-", $str);
    $str = str_replace("'", "-", $str);
    $str = preg_replace('/\-\-+/', '-', $str);
    $str = rtrim($str, '-');
    return $str;
}

function addhttp($url){
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

function filter_inputs($data){
	$filter = trim(strip_tags($data));
	return $filter;
}

if (!isset($_SESSION['token'])){
    $token = md5(uniqid(rand(), TRUE));
    $_SESSION['token'] = $token;
    $_SESSION['token_time'] = time();
}else{
    $token = $_SESSION['token'];
}

function url_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}


$sitekey	 = "6Ldr630cAAAAAC9pSrB_OcqW2F27fA6NFGOLHx6L";
$secretKey 	 = "6Ldr630cAAAAAM4ucNbazZxjZ0rtxoiRoHyAxQFV"; 

?>