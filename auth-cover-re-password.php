<?php
include("includes/connection.php");
include("includes/mailconfig.php");

if (isset($_SESSION['user_id'])):
    header("location:user-dashboard");
endif;

$page 		= "reset";
$pageurl	= "auth-cover-re-password";
$loginurl	= "auth-cover-login";
$successurl	= "user-dashboard";

$stage = $connect->real_escape_string(@$_POST['stage']);
$csrf   = $connect->real_escape_string(filter_inputs($_POST['csrf']));
$captcha = $connect->real_escape_string($_POST['recaptchaResponse']);

if ($stage == 1 && $csrf == $_SESSION["token"] ) {
	$_SESSION['token'] = md5(uniqid(rand(), TRUE));
	$email 		= $connect->real_escape_string($_POST['email']);
    
	// Build POST request:
    $recaptcha_url 		= 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret 	= $secretKey;
    $recaptcha_response = $connect->real_escape_string($_POST['recaptchaResponse']);

    // Make and decode POST request:
    //$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);	
    $recaptcha = url_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);	
    $recaptcha = json_decode($recaptcha);

	if ($recaptcha->score >= 0.5) {
		
		$query = db_query("SELECT * FROM mng_members WHERE email='{$email}' and user_status='1' ");
		$resuser = mysqli_num_rows($query);
		if($resuser > 0){
		$row = mysqli_fetch_array($query);
		$fname = $row['fname'];
		/* Send user passwod to registered mail id */
		$url = SITEURL.'resetpassword?reset='.base64_encode($email).'&status='.base64_encode('forgot');

		$message='<table style="box-sizing: border-box; width: 100%; max-width: 500px; border-radius: 6px; overflow: hidden; background-color: #fff; border:1px solid #2f55d4; box-shadow: 0 0 3px rgba(60, 72, 88, 0.15); margin:15px auto;">
	<thead>
		<tr style="background-color: #2f55d4; padding: 3px 0; line-height: 68px; text-align: center; color: #fff; font-size: 24px; font-weight: 700; letter-spacing: 1px;">
			<th scope="col"><img src="'.SITEURL.'images/mail_logo.png" height="30" alt=""></th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td style="padding: 48px 24px 0; color: #161c2d; font-size: 18px; font-weight: 600;">
				Hello  '.$fname.',
			</td>
		</tr>
		<tr>
			<td style="padding: 15px 24px 15px; color: #8492a6;">
				Reset your password by clicking the link below :
			</td>
		</tr>

		<tr>
			<td style="padding: 15px 24px;">
				<a href="'.$url.'" style="padding: 8px 20px; outline: none; text-decoration: none; font-size: 16px; letter-spacing: 0.5px; transition: all 0.3s; font-weight: 600; border-radius: 6px;">Reset Password</a>
			</td>
		</tr>

		<tr>
			<td style="padding: 15px 24px 0; color: #8492a6;">
				More Text goes here...
			</td>
		</tr>

		<tr>
			<td style="padding: 15px 24px 15px; color: #8492a6;">
				A2Z Webhelp <br> Support Team
			</td>
		</tr>

		<tr>
			<td style="padding: 16px 8px; color: #8492a6; background-color: #f8f9fc; text-align: center;">
				© 2021-22 A2ZWebhelp.
			</td>
		</tr>
	</tbody>
</table>';

	require 'includes/mailclass/Exception.php';
	require 'includes/mailclass/PHPMailer.php';
	require 'includes/mailclass/SMTP.php';
	
	$subject = "Reset Your A2ZWebhelp Password";
	$mail = new PHPMailer\PHPMailer\PHPMailer(); 
	

	$mail->isSMTP();                    //Send using SMTP
	$mail->Host       = $mailHost;      //Set the SMTP server to send through
	$mail->SMTPAuth   = true;           //Enable SMTP authentication
	$mail->Username   = $mailUsername;  //SMTP username
	$mail->Password   = $mailPassword;  //SMTP password
	$mail->Port       = $mailPort;
	
	$mail->setFrom($Sender, 'A2zWebhelp',0);
	$mail->addAddress($email);
	$mail->Subject  = $subject;
	
	$mail->isHTML(true);
	$mail->Body  = $message;
	$mail->send();
	
		$msg = base64_encode("A link to reset your password has been sent to your email");
		setcookie("msg", $msg, time() + 3);
		header("Location:$pageurl");
		exit;
	}else{
		$msg = base64_encode("Invalid email. Please try again!");
		setcookie("msg", $msg, time() + 3);
		header("Location:$pageurl");
		exit;
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
        <section class="cover-user bg-white">
            <div class="container-fluid px-0">
                <div class="row no-gutters position-relative">
                    <div class="col-lg-4 cover-my-30 order-2">
                        <div class="cover-user-img d-flex align-items-center">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card login_page border-0" style="z-index: 1">
                                        <div class="card-body p-0">
                                            <h4 class="card-title text-center">Recover Account</h4>  
<form name="forgot-form" id="forgot-form" method="post" action="">
<input class="form-control input-box" type="hidden" name="stage" value="1">
<input type="hidden" name="csrf" value="<?php print $_SESSION["token"]; ?>">
<input type="hidden" name="recaptchaResponse" id="recaptchaResponse">
	<div class="row">
		<div class="col-lg-12">
<?php if ($_COOKIE['msg']) { ?>
<div class="clearfix"></div>	
<div class="alert alert-success">
	<a href="#" class="close" data-dismiss="alert" onClick="$('.alert').hide('slow');">&times;</a>
	<?php print str_replace("+", " ", base64_decode($_COOKIE['msg'])); ?>
</div>
<?php } ?>		
			<p class="text-muted">Please enter your email address. You will receive a link to create a new password via email.</p>
			<div class="form-group position-relative">
				<label>Email address <span class="text-danger">*</span></label>
				<i data-feather="mail" class="fea icon-sm icons"></i>
				<input type="email" class="form-control pl-5" placeholder="Enter Your Email Address" id="email" name="email" required="" autocomplete="off">
			</div>
		</div><!--end col-->
		<div class="col-lg-12">
			<button class="btn btn-primary btn-block">Send</button>
		</div><!--end col-->
		<div class="mx-auto">
			<p class="mb-0 mt-3"><small class="text-dark mr-2">Remember your password ?</small> <a href="auth-cover-login" class="text-dark font-weight-bold">Sign in</a></p>
		</div><!--end col-->
	</div><!--end row-->
</form>
                                        </div>
                                    </div>
                                </div><!--end col-->
                            </div><!--end row-->
                        </div> <!-- end about detail -->
                    </div> <!-- end col -->    

                    <div class="col-lg-8 offset-lg-4 padding-less img order-1" style="background-image:url('images/forgot.jpg')"></div><!-- end col -->    
                </div><!--end row-->
            </div><!--end container fluid-->
        </section><!--end section-->
        <!-- Hero End -->


        <!-- javascript -->
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/jquery.easing.min.js"></script>
        <!-- Icons -->
        <script src="js/feather.min.js"></script>
        <!-- Main Js -->
        <script src="js/app.js"></script>
		<script src="js/jquery.validate.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js?render=<?php print $sitekey; ?>"></script><script>

$(function() {
  $("#forgot-form").validate({
    rules: {
      email: {
        required: true,
        email: true
      }
    },
    messages: {
      email: "Please enter a valid email address",
    },
    errorPlacement: function(error, element){
      error.insertAfter(element);
    }
  });
});

grecaptcha.ready(function () {
	grecaptcha.execute('<?php print $sitekey; ?>',{action:'login'}).then(function(token){
		var recaptchaResponse = document.getElementById('recaptchaResponse');
		recaptchaResponse.value = token;
	});
});
</script>		
</body>
</html>