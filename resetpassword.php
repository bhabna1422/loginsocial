<?php
include("includes/connection.php");
include("includes/mailconfig.php");

if (isset($_SESSION['user_id'])):
    header("location:user-dashboard");
endif;

$page 		= "reset";
$pageurl	= "resetpassword";
$successurl	= "auth-login";


$stage 		= $connect->real_escape_string(@$_POST['stage']);
$csrf   	= $connect->real_escape_string(filter_inputs($_POST['csrf']));
$captcha 	= $connect->real_escape_string($_POST['recaptchaResponse']);

if ($stage == 1 && $csrf == $_SESSION["token"] ) {
  $_SESSION['token'] = md5(uniqid(rand(), TRUE));
  $email 	= $connect->real_escape_string(base64_decode($_GET['reset']));
  $pwd 		= $connect->real_escape_string(MD5($_POST['password']));
  $date 	= date("Y-m-d H:i:s");

	// Build POST request:
    $recaptcha_url 		= 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret 	= $secretKey;
    $recaptcha_response = $connect->real_escape_string($_POST['recaptchaResponse']);

    // Make and decode POST request:
    $recaptcha = url_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);	
    $recaptcha = json_decode($recaptcha);

	if ($recaptcha->score >= 0.5) {
  
	if($pwd!=""){
	  
		$query = db_query("update mng_members set pwd='".$pwd."' where email='".$email."'");	
	  
		$msg1 = base64_encode("Password Changed Successfully!");
		setcookie("msg", $msg1, time() + 3);
		header("location:$successurl");
		exit();
	}
	}else{
		$msg = "Please Check Captcha Error.";
		setcookie("msg", $msg, time() + 3);
		header("Location: $pageurl?reset=".base64_encode($email));
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
                            <img src="images/forgot.svg" class="img-fluid d-block mx-auto" alt="">
							<a href="https://storyset.com/mobile" style="font-size:10px; color:#FFF;">Mobile illustrations by Storyset</a>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6">
                        <div class="card login_page shadow rounded border-0">
                            <div class="card-body">
                                <h4 class="card-title text-center">Reset Your Password</h4>  

<form name="reset-form" id="reset-form" method="post" action="" onsubmit="return chkpassword();">
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
	<div class="col-lg-12">
		<button class="btn btn-primary btn-block">Send</button>
	</div>
	<div class="mx-auto">
		<p class="mb-0 mt-3"><small class="text-dark mr-2">Remember your password?</small> <a href="auth-login" class="text-dark font-weight-bold">Sign in</a></p>
	</div>
</div>
                                </form>
                            </div>
                        </div>
                    </div> <!--end col-->
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
  $("#reset-form").validate({
    rules: { 
      password: {
        required: true,
        minlength: 5
      },
      cpassword: {
         required: true,
          minlength: 5,
          equalTo: "#password"
      },
    },
    messages: {   
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },      
      cpassword: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long",
        equalTo: "Please enter the same password as above"
      },
      
    },
     errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
  });
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
    </body>
</html>