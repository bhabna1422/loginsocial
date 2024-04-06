<?php 
include("includes/connection.php");
include("includes/mailconfig.php");
require_once 'Facebook/settings.php';
if (isset($_SESSION['user_id'])):
    header("location:user-dashboard");
endif;

$page 		= "signup";
$pageurl	= "auth-signup-three";
$loginurl	= "auth-login-three";
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
$stage 	= $connect->real_escape_string(@$_POST['stage']);
$csrf   = $connect->real_escape_string(filter_inputs($_POST['csrf']));
$captcha= $connect->real_escape_string($_POST['recaptchaResponse']);


if ($stage == 1 && $csrf == $_SESSION["token"] ) {
  $_SESSION['token'] = md5(uniqid(rand(), TRUE));
  $fname = $connect->real_escape_string($_POST['fname']);
  $lname = $connect->real_escape_string($_POST['lname']);
  $email = $connect->real_escape_string($_POST['email']);
  $pwd 	 = $connect->real_escape_string(MD5($_POST['password']));
  $date  = date("Y-m-d H:i:s");

	// Build POST request:
    $recaptcha_url 		= 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret 	= $secretKey;
    $recaptcha_response = $connect->real_escape_string($_POST['recaptchaResponse']);

    // Make and decode POST request:
    $recaptcha = url_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);	
    $recaptcha = json_decode($recaptcha);

if ($recaptcha->score >= 0.5) {
  
  if($fname!="" or $email!="" or $pwd!=""){
	  
	$query		= db_query("select * from mng_members where email='".$email."'");	
	$resuser = mysqli_num_rows($query);
	if($resuser > 0){
		$msg1 = base64_encode("Email not Available!");
		setcookie("msg", $msg1, time() + 3);
		header("location:$pageurl");
		exit();
	}else{  
	  $mysql = "Insert into mng_members set fname='".$fname."',lname='".$lname."',email='".$email."',pwd='".$pwd."',reg_date='".$date."',user_status='0'";

	  $query = db_query($mysql);
	}  

// $inserted_id = mysqli_insert_id($connect);
/* Send Activation Email */

	
	$url = SITEURL.'activate.php?id='.base64_encode($email);

	
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
				Thanks for creating an A2Z Webhelp account. To continue, please confirm your email address by clicking the button below :
			</td>
		</tr>

		<tr>
			<td style="padding: 15px 24px;">
				<a href="'.$url.'" style="padding: 8px 20px; outline: none; text-decoration: none; font-size: 16px; letter-spacing: 0.5px; transition: all 0.3s; font-weight: 600; border-radius: 6px;">Confirm Email Address</a>
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
	
	$subject = "Activate Your A2ZWebhelp Account";
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
	

	$msg1 = base64_encode("<b>Registered Successfully!</b> Please check your email to activate your account.");
	setcookie("msg", $msg1, time() + 3);
	header("location:$loginurl");
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
        <section class="bg-home bg-circle-gradiant d-flex align-items-center">
            <div class="bg-overlay bg-overlay-white"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-8"> 
                        <div class="card login_page shadow rounded border-0">
                            <div class="card-body">
<?php if ($_COOKIE['msg']) { ?>
<div class="clearfix"></div>	
<div class="alert alert-success">
<a href="#" class="close" data-dismiss="alert" onClick="$('.alert').hide('slow');">&times;</a>
<?php print str_replace("+", " ", base64_decode($_COOKIE['msg'])); ?>
</div>
<?php } ?>							
                                <h4 class="card-title text-center">Signup</h4>  
<form name="reg-form" id="reg-form" method="post" action="" onsubmit="return chkpassword();">
<input class="form-control input-box" type="hidden" name="stage" value="1">
<input type="hidden" name="csrf" value="<?php print $_SESSION["token"]; ?>">
<input type="hidden" name="recaptchaResponse" id="recaptchaResponse">
                                    <div class="row">

                                        <div class="col-md-6">
			<div class="form-group position-relative">                                               
				<label>First name <span class="text-danger">*</span></label>
				<i data-feather="user" class="fea icon-sm icons"></i>
				<input type="text" class="form-control pl-5" placeholder="First Name" name="fname"id="fname" autocomplete="off" required="" />
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group position-relative">                                                
				<label>Last name <span class="text-danger">*</span></label>
				<i data-feather="user-check" class="fea icon-sm icons"></i>
				<input type="text" class="form-control pl-5" placeholder="Last Name" name="lname" id="lname" autocomplete="off"   />
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group position-relative">
				<label>Your Email <span class="text-danger">*</span></label>
				<i data-feather="mail" class="fea icon-sm icons"></i>
				<input type="email" class="form-control pl-5" placeholder="Email" id="email" name="email"required="" autocomplete="off" required="" >
				<div class="clearfix" >
				<span id="emailstatus" style="color: #ff0000;"></span>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group position-relative">
				<label>Password <span class="text-danger">*</span></label>
				<i data-feather="key" class="fea icon-sm icons"></i>
				<input type="password" class="form-control pl-5" placeholder="Password" required="" name="password" id="password" onclick="hideme('passerror')">
				<a href="#!" onclick="show()" class="eye-visiable"> <i class="fa fa-eye-slash " id="icon"  aria-hidden="true"></i></a>
				<meter max="4" id="password-strength-meter"></meter>
				<div class="errorTxt error" id="passerror">
					- Password must be 5 character long <br/>
					- Must have one special character [!@#$%^&*()] <br/>
					- Must have character and number.
				</div>
			</div>
		</div>
		
		<div class="col-md-12">
			<div class="form-group position-relative">
				<label>Confirm Password <span class="text-danger">*</span></label>
				<i data-feather="key" class="fea icon-sm icons"></i>
				<input type="password" class="form-control pl-5" placeholder="Confirm Password" required="" name="cpassword" id="cpassword" onclick="hideme('passerror')">
				<a href="#!" onclick="showcpwd()" class="eye-visiable">
                <i class="fa fa-eye-slash" id="icon2"  aria-hidden="true"></i></a>
			</div>
		</div>
		
		
		<div class="col-md-12">
			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="customCheck1" name="regcheck" required="">
					<label class="custom-control-label" for="customCheck1">I Accept <a href="#" class="text-primary">Terms And Condition</a></label>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<button class="btn btn-primary btn-block">Register</button>
		</div>
                                        <div class="col-lg-12 mt-4 text-center">
                                            <h6>Or Signup With</h6>
<ul class="list-unstyled social-icon mb-0 mt-3">
<li class="list-inline-item"><a href="social_signup.php?sm=Facebook" class="rounded"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
<li class="list-inline-item"><a href="social_signup.php?sm=Google" class="rounded"><i class="fa fa-google" aria-hidden="true"></i>
</a></li>
<li class="list-inline-item"><a href="social_signup.php?sm=Twitter" class="rounded"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
</ul><!--end icon-->
                                        </div>
                                        <div class="mx-auto">
                                            <p class="mb-0 mt-3"><small class="text-dark mr-2">Already have an account ?</small> <a href="auth-login-three.php" class="text-dark font-weight-bold">Sign in</a></p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div> <!--end container-->
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
		<script src="js/password-srtength.js"></script>
		<script type="text/javascript">

$(function() {
  $("#reg-form").validate({
    rules: {
      fname: "required",
      email: {
        required: true,
        email: true
      },   
      password: {
        required: true,
        minlength: 5
      },
      cpassword: {
         required: true,
          minlength: 5,
          equalTo: "#password"
      },
	  regcheck:"required"
    },
    messages: {
      name: "Please enter your first name",
      email: "Please enter a valid email address",     
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },      
      cpassword: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long",
        equalTo: "Please enter the same password as above"
      },
	  regcheck:{
		required: "Please accept the terms and conditions!" 
	  }
      
    },
     errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
  });
});


$(document).on('blur','#email',function(){
  var email = $('#email').val();
  if(email!=""){
	var url = "ajax_email.php";
	// $("#status").html('<img src="loader.gif" align="absmiddle">&nbsp;Checking availability...'); 
		$.ajax({
		  type: "POST",
		  url : url,
		  data: $("#email").serialize(),
		  success: function(msg){
			if(msg == 'OK'){         
				$("#emailstatus").hide();
			}else{  
			 $("#email").val('');
			 $("#emailstatus").show();
			 $("#emailstatus").html('Email already used. Try another!');
		}   
		   }
	   });          
	return false;
  }
});

  function show(){
    var inputPass2 = document.getElementById('password'),
    icon = document.getElementById('icon');
    if(inputPass2.type == 'password') {
        inputPass2.setAttribute('type', 'text');
        icon.className = 'fa fa-eye';
     } else {
        inputPass2.setAttribute('type', 'password');
        icon.className = 'fa fa-eye-slash';
       
    }
  }
   function showcpwd(){
    var inputPass2 = document.getElementById('cpassword'),
    icon = document.getElementById('icon2');
    if(inputPass2.type == 'password') {
        inputPass2.setAttribute('type', 'text');
        icon.className = 'fa fa-eye';
     } else {
        inputPass2.setAttribute('type', 'password');
        icon.className = 'fa fa-eye-slash';
       
    }
  }  

/* CHECK PASSWORD STRANGTH */  
var strength = {
        0: "Worst",
        1: "Bad",
        2: "Weak",
        3: "Good",
        4: "Strong"
}

var password = document.getElementById('password');
var meter 	 = document.getElementById('password-strength-meter');
//var text = document.getElementById('password-strength-text');

password.addEventListener('input', function()
{
    var val = password.value;
    var result = zxcvbn(val);
    
    // Update the password strength meter
    meter.value = result.score;
   
});

function chkpassword(){
	var letterNumber = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{4,30}$/;  
	inputtxt = $("#password").val();
	len = inputtxt.length;
	 if(inputtxt.match(letterNumber)){  
	   return true;  
	  }  
	else  
	  {    
	   $("#passerror").show();
	   return false;   
	  }  
	  if(len > 4){
		  return true;
	  }else{
		  //alert("Your password must be 8 character long and alphanumeric with special character");   
		  $("#passerror").show();
		return false; 
	  }
}

function hideme(val){
	$("#"+val).hide();
}

</script>
<script src="https://www.google.com/recaptcha/api.js?render=<?php print $sitekey; ?>"></script>
<script>
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