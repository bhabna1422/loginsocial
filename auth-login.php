<?php
header('X-Frame-Options: SAMEORIGIN');
header("X-XSS-Protection: 1; mode=block");
header('X-Content-Type-Options: nosniff');

include("includes/connection.php");
require_once 'Facebook/settings.php';

if (isset($_SESSION['user_id'])):
    header("location:user-dashboard");
endif;

$page 		= "login";
$pageurl	= "auth-login";
$successurl	= "user-dashboard";


// ---------- Cookie Info ---------- //
$cookie_name = 'a2zlogin';
$cookie_time = (3600 * 24 * 30); // 30 days
//------------- AUTO LOGIN ----------------//

if(isSet($cookie_name)){
// Check if the cookie exists
	if(isSet($_COOKIE[$cookie_name])){
		parse_str($_COOKIE[$cookie_name]);

		$_SESSION['email']		= $email;
		$_SESSION['fname']		= $fname;
		$_SESSION['user_id']	= $user_id;
		
		header("location:$successurl");
		exit;
	}
}
$stage = $connect->real_escape_string($_POST['stage']);
$csrf   = $connect->real_escape_string(filter_inputs($_POST['csrf']));
$captcha = $connect->real_escape_string($_POST['recaptchaResponse']);

if ($stage == 1 && $csrf == $_SESSION["token"] ){
	
	$_SESSION['token'] = md5(uniqid(rand(), TRUE));
	$email 		= $connect->real_escape_string(strtolower($_POST['email']));
    $password 	= $connect->real_escape_string(MD5($_POST['password']));

	// Build POST request:
    $recaptcha_url 		= 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret 	= $secretKey;
    $recaptcha_response = $connect->real_escape_string($_POST['recaptchaResponse']);

    // Make and decode POST request:
    //$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);	
    $recaptcha = url_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);	
    $recaptcha = json_decode($recaptcha);

	if ($recaptcha->score = 0.5){
	
	$sql = db_query("select * from mng_members where  (lower(email)='".$email."') && pwd='".$password."' && user_status='1'");
	
	$res = mysqli_fetch_object($sql);  
    $co= mysqli_num_rows($sql);
	
	if (mysqli_num_rows($sql)){
		if ($res->email == $email && $res->pwd == $password){
			$_SESSION['email'] = $res->email;
			$_SESSION['fname'] = $res->fname;
			$_SESSION['user_id'] = $res->id;
			
			if($_POST['remember']==1){
				setcookie ($cookie_name, 'fname='.$res->fname.'&email='.$res->email.'&user_id='.$res->id, time() + $cookie_time);
			}
					
			header("location:$successurl");
			exit();
		}
	}else{
		$msg = base64_encode("Invalid UserName/Password.");
		setcookie("msg", $msg, time() + 3);
		header("location:$pageurl");
		exit();			
	}
	}else{
		$msg = base64_encode("Please Check Captcha Error.");
		setcookie("msg", $msg, time() + 3);
		header("Location:$pageurl");
		exit;
	}
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Advanced Security - PHP Register/Login System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Advanced Security - PHP & MySQL Register/Login System with  Bootstrap 4 " />
<meta name="keywords" content="PHP, MySQL, Bootstrap, Login, Register,Security" />
<meta name="Version" content="v2.0.0" />
<!-- favicon -->
<link rel="shortcut icon" href="images/favicon.ico">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- Main Css -->
<link href="css/style.css" rel="stylesheet" type="text/css" id="theme-opt" />
<link href="css/custom.css" rel="stylesheet" type="text/css" id="theme-opt" />
<script src="https://use.fontawesome.com/ab695b83ef.js"></script>
</head>

    <body>

        <div class="back-to-home rounded d-none d-sm-block">
            <a href="index.php" class="btn btn-icon btn-soft-primary"><i data-feather="home" class="icons"></i></a>
        </div>

<!-- Hero Start -->
<section class="bg-home d-flex align-items-center">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-7 col-md-6">
				<div class="mr-lg-5">   
					<img src="images/login_right.png" class="img-fluid d-block mx-auto" alt="">
				</div>
			</div>
<div class="col-lg-5 col-md-6">
	<div class="card login-page bg-white shadow rounded border-0">
		<div class="card-body">
<?php if ($_COOKIE['msg']) { ?>
<div class="clearfix"></div>	
<div class="alert alert-success">
	<a href="#" class="close" data-dismiss="alert" onClick="$('.alert').hide('slow');">&times;</a>
	<?php print str_replace("+", " ", base64_decode($_COOKIE['msg'])); ?>
</div>
<?php } ?>
			<h4 class="card-title text-center">Login</h4>  
			<form name="login-form" id="login-form" method="post" action="">
<input class="form-control input-box" type="hidden" name="stage" value="1">
<input type="hidden" name="csrf" value="<?php print $_SESSION["token"]; ?>">
<input type="hidden" name="recaptchaResponse" id="recaptchaResponse">
				<div class="row">
				
					<div class="col-lg-12">
						<div class="form-group position-relative">
							<label>Your Email <span class="text-danger">*</span></label>
							<i data-feather="user" class="fea icon-sm icons"></i>
							<input type="email" class="form-control pl-5" placeholder="Email" id="email" name="email" required autocomplete="off">
						</div>
					</div>

					<div class="col-lg-12">
						<div class="form-group position-relative">
							<label>Password <span class="text-danger">*</span></label>
							<i data-feather="key" class="fea icon-sm icons"></i>
							<input type="password" class="form-control pl-5" placeholder="Password" id="password" name="password" required autocomplete="off">
						</div>
					</div>

					<div class="col-lg-12">
						<div class="d-flex justify-content-between">
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" name="remember" id="customCheck1" value="1" >
									
									<label class="custom-control-label" for="customCheck1">Remember me</label>
								</div>
							</div>
							<p class="forgot-pass mb-0"><a href="auth-re-password" class="text-dark font-weight-bold">Forgot password?</a></p>
						</div>
					</div>
					<div class="col-lg-12 mb-0">
						<button type="submit" name="submit" class="btn btn-primary btn-block">Sign in</button>
					</div>
<div class="col-lg-12 mt-4 text-center">
<h6>Or Login With</h6>
<ul class="list-unstyled social-icon mb-0 mt-3">
<li class="list-inline-item"><a href="social_signup.php?sm=Facebook" class="rounded"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
<li class="list-inline-item"><a href="social_signup.php?sm=Google" class="rounded"><i class="fa fa-google" aria-hidden="true"></i>
</a></li>
<li class="list-inline-item"><a href="social_signup.php?sm=Twitter" class="rounded"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
<li class="list-inline-item"><a href="social_signup.php?sm=Linkedin" class="rounded"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
</ul><!--end icon-->
</div>
					<div class="col-12 text-center">
						<p class="mb-0 mt-3"><small class="text-dark mr-2">Don't have an account?</small> <a href="auth-signup" class="text-dark font-weight-bold">Sign Up</a></p>
					</div>
				</div>
			</form>
		</div>
	</div><!---->
</div> <!--end col-->
		</div><!--end row-->
	</div> <!--end container-->
</section><!--end section-->
        <!-- Hero End -->

        <!-- javascript -->
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/jquery.easing.min.js"></script>
		<script src="js/jquery.validate.min.js"></script>
        <!-- Icons -->
        <script src="js/feather.min.js"></script>
        <!-- Main Js -->
        <script src="js/app.js"></script>
		
<script src="https://www.google.com/recaptcha/api.js?render=<?php print $sitekey; ?>"></script>
<script type="text/javascript">
$(function() {
  $("#login-form").validate({
    rules: {
      email: {
        required: true,
        email: true
      },   
      password: {
        required: true,
        minlength: 5
      },
    },
    messages: {
      email: "Please enter a valid email address",     
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
    },
     errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
  });
});
grecaptcha.ready(function () {
	grecaptcha.execute('<?php print $sitekey; ?>',{action:''}).then(function(token){
		var recaptchaResponse = document.getElementById('recaptchaResponse');
		recaptchaResponse.value = token;
	});
});
</script>	


<div id="fb-root" style="float:left; width:1px;"></div>
<script>
window.fbAsyncInit = function() {
	FB.init({
	    appId: '<?php echo App_ID;?>',
        cookie: true,
       	xfbml: true,
        oauth: true
   });      
};
(function() {
	var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
}());
</script>
</body>
</html>