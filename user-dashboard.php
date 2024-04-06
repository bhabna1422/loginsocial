<?php
header('X-Frame-Options: SAMEORIGIN');
header("X-XSS-Protection: 1; mode=block");
header('X-Content-Type-Options: nosniff');

include("includes/connection.php");


if (!isset($_SESSION['user_id'])):
    header("location:auth-login");
endif;

$pageurl = "user-dashboard";

$userid = $_SESSION['user_id'];
$stage = $connect->real_escape_string($_POST['stage']);
$csrf   = $connect->real_escape_string(filter_inputs($_POST['csrf']));
$captcha = $connect->real_escape_string($_POST['recaptchaResponse']);
$allowed_extensions = array('jpeg', 'jpg','png', 'gif');

if ($stage == 1 && $csrf == $_SESSION["token"] ) {
	$_SESSION['token'] = md5(uniqid(rand(), TRUE));
	
	$fname			= $connect->real_escape_string($_POST['fname']);
	$lname			= $connect->real_escape_string($_POST['lname']);
	$email			= $connect->real_escape_string($_POST['email']);
	$phone			= $connect->real_escape_string($_POST['phone']);
	$description	= $connect->real_escape_string($_POST['description']);
	$occupation		= $connect->real_escape_string($_POST['occupation']);
	$external_link	= $connect->real_escape_string($_POST['external_link']);
	
	// Build POST request:
    $recaptcha_url 		= 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret 	= $secretKey;
    $recaptcha_response = $connect->real_escape_string($_POST['recaptchaResponse']);

    // Make and decode POST request:
    //$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);	
    $recaptcha = url_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);	
    $recaptcha = json_decode($recaptcha);

	if ($recaptcha->score = 0.5) {
		
		if ($_FILES['profilepic']['name'] != "") {
        $filenamenew = $_FILES['profilepic']['name'];
        $path_info = pathinfo($filenamenew);
        $is_valid = in_array($path_info['extension'], $allowed_extensions);

        if (empty($is_valid)) {
            $msg = base64_encode("Incorrect file extension, Please upload a valid image file");
            setcookie("msg", $msg, time() + 3);
			header("location:".$pageurl);
			exit();
        } else {
            $path2 = "profilepic";
            $s1 = rand();
            $realname = removeSpchar($_FILES['profilepic']['name']);
            $realname = $s1 . "_" . $realname;
            $dest = $path2 . "/" . $realname;
            move_uploaded_file($_FILES['profilepic']['tmp_name'],$dest);
            $img_name = trim($realname);
            $profilepic = $path2 . "/" .$img_name;
			$path3 = "profilepic";
            $delpath1 = $path3 . "/" . $_POST['T2'];
            @unlink($delpath1);
        }
    }else {
        $profilepic = trim($_POST['T2']);
    }
	
		db_query("update `mng_members` set fname='".$fname."', lname='".$lname."', email='".$email."', phone='".$phone."', profilepic='".$profilepic."', external_link='".$external_link."', occupation='".$occupation."', description='".$description."' WHERE id='".$userid."'");
		
		$msg = base64_encode("User Updated Successfully.");
		setcookie("msg", $msg, time() + 3);
		header("location:$pageurl");
		exit();
		
	}else{
		$msg = base64_encode("Please Check Captcha Error.");
		setcookie("msg", $msg, time() + 3);
		header("Location:$pageurl");
		exit;
	}
}

/* DELETE USER IMAGES */

$delid = $connect->real_escape_string(filter_inputs($_GET['delid']));

if($delid !=""){
    $sql = db_query("select profilepic from mng_members where id=".$delid);
    $row = mysqli_fetch_array($sql);
    $profilepic = $row['profilepic'];
    $delpath ="profilepic/".$profilepic;
    @unlink($delpath);
    $mysql = "update mng_members set profilepic='' where id='$delid'";
    db_query($mysql);
    
    $msg = base64_encode("Profile Picture Deleted Successfully.");
    setcookie("msg", $msg, time() + 3);
	header("location:".$pageurl);
	exit();
}

/* GET USER INFORMATION */
$sql = db_query("Select * from `mng_members` WHERE id='".$userid."'");
$row = mysqli_fetch_array($sql);

$fname 			= $row['fname'];
$lname 			= $row['lname'];
$email 			= $row['email'];
$description 	= $row['description'];
$occupation 	= $row['occupation'];
$profilepic 	= $row['profilepic'];
$phone 			= $row['phone'];
$external_link  = $row['external_link'];

if($profilepic==""){
	$pimage = "images/prifile_default.png";
}else{
    
	$pimage = $profilepic;
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

	<header id="topnav" class="defaultscroll sticky">
            <div class="container">
                <!-- Logo container-->
                <div>
                    <a class="logo" href="index.php">
                        <img src="images/logo.png" height="50" alt="">
                    </a>
                </div>                 
                <div class="buy-button">
                    <a href="../buyauthregister.php?page=a2z-login-register" target="_blank" class="btn btn-primary download1">Buy Now</a>
                </div><!--end login button-->
                <!-- End Logo container-->
                <div class="menu-extras">
                    <div class="menu-item">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </div>
                </div>
        
                <div id="navigation">
                    <!-- Navigation Menu-->   
                    <ul class="navigation-menu">
<li><a href="index.php">Home</a></li>
<li><a href="user-dashboard">Dashboard</a></li>
<li><a href="logout">Logout</a></li>
<li><a href="documentation/index.html" target="_blank">Documentation</a></li>
                    </ul><!--end navigation menu-->
                    <div class="buy-menu-btn d-none">
                        <a href="#" target="_blank" class="btn btn-primary">Buy Now</a>
                    </div><!--end login button-->
                </div><!--end navigation-->
            </div><!--end container-->
        </header><!--end header-->

        
        <!-- Hero Start -->
        <section class="bg-profile d-table w-100 bg-primary" style="background: url('images/bg.png') center center;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card public-profile border-0 rounded shadow" style="z-index: 1;">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-lg-2 col-md-3 text-md-left text-center">
                                   
                                        <img src="<?php echo $pimage?>" class="avatar avatar-large rounded-circle shadow d-block mx-auto" alt="">
                                    </div><!--end col-->
    
<div class="col-lg-10 col-md-9">
<div class="row align-items-end">
	<div class="col-md-7 text-md-left text-center mt-4 mt-sm-0">
		<h3 class="title mb-0"><?php echo $fname." ".$lname;?></h3>
		<small class="text-muted h6 mr-2"><?php echo $occupation; ?></small>
		<ul class="list-inline mb-0 mt-3">
			<li class="list-inline-item mr-2"><a href="javascript:void(0)" class="text-muted" title="Instagram"><i data-feather="instagram" class="fea icon-sm mr-2"></i>instagram</a></li>
			<li class="list-inline-item"><a href="javascript:void(0)" class="text-muted" title="Linkedin"><i data-feather="linkedin" class="fea icon-sm mr-2"></i>linkedin</a></li>
		</ul>
	</div><!--end col-->
	<div class="col-md-5 text-md-right text-center">
		<ul class="list-unstyled social-icon social mb-0 mt-4">
			<li class="list-inline-item"><a href="javascript:void(0)" class="rounded" data-toggle="tooltip" data-placement="bottom" title="Add Friend"><i data-feather="user-plus" class="fea icon-sm fea-social"></i></a></li>
			<li class="list-inline-item"><a href="javascript:void(0)" class="rounded" data-toggle="tooltip" data-placement="bottom" title="Messages"><i data-feather="message-circle" class="fea icon-sm fea-social"></i></a></li>
			<li class="list-inline-item"><a href="javascript:void(0)" class="rounded" data-toggle="tooltip" data-placement="bottom" title="Notifications"><i data-feather="bell" class="fea icon-sm fea-social"></i></a></li>
			<li class="list-inline-item"><a href="account-setting.php" class="rounded" data-toggle="tooltip" data-placement="bottom" title="Settings"><i data-feather="tool" class="fea icon-sm fea-social"></i></a></li>
		</ul><!--end icon-->
	</div><!--end col-->
</div><!--end row-->
</div><!--end col-->
                                </div><!--end row-->
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--ed container-->
        </section><!--end section-->
        <!-- Hero End -->

        <!-- Profile Start -->
        <section class="section mt-60">
            <div class="container mt-lg-3">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-12 d-lg-block d-none">
                        <div class="sidebar sticky-bar p-4 rounded shadow">
                            <div class="widget">
                                <h5 class="widget-title">Followers :</h5>
                                <div class="row mt-4">
                                    <div class="col-6 text-center">
                                        <i data-feather="user-plus" class="fea icon-ex-md text-primary mb-1"></i>
                                        <h5 class="mb-0">2588</h5>
                                        <p class="text-muted mb-0">Followers</p>
                                    </div><!--end col-->

                                    <div class="col-6 text-center">
                                        <i data-feather="users" class="fea icon-ex-md text-primary mb-1"></i>
                                        <h5 class="mb-0">454</h5>
                                        <p class="text-muted mb-0">Following</p>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </div>

                            <div class="widget mt-4 pt-2">
                                <h5 class="widget-title">Projects :</h5>
                                <div class="progress-box mt-4">
                                    <h6 class="title text-muted">Progress</h6>
                                    <div class="progress">
                                        <div class="progress-bar position-relative bg-primary" style="width:50%;">
                                            <div class="progress-value d-block text-muted h6">24 / 48</div>
                                        </div>
                                    </div>
                                </div><!--end process box-->
                            </div>
                            
                            <div class="widget">
                                <div class="row">
                                    <div class="col-6 mt-4 pt-2">
                                        <a href="user-dashboard" class="accounts active rounded d-block shadow text-center py-3">
                                            <span class="pro-icons h3 text-muted"><i class="uil uil-setting"></i></span>
                                            <h6 class="title text-dark h6 my-0">Settings</h6>
                                        </a>
                                    </div><!--end col--> 
									
									<div class="col-6 mt-4 pt-2">
                                        <a href="change_password" class="accounts rounded d-block shadow text-center py-3">
                                            <span class="pro-icons h3 text-muted"><i class="uil uil-setting"></i></span>
                                            <h6 class="title text-dark h6 my-0">Change Password</h6>
                                        </a>
                                    </div><!--end col-->

                                    <div class="col-6 mt-4 pt-2">
                                        <a href="logout" class="accounts rounded d-block shadow text-center py-3">
                                            <span class="pro-icons h3 text-muted"><i class="uil uil-sign-out-alt"></i></span>
                                            <h6 class="title text-dark h6 my-0">Logout</h6>
                                        </a>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </div>

                            <div class="widget mt-4 pt-2">
                                <h5 class="widget-title">Follow me :</h5>
                                <ul class="list-unstyled social-icon mb-0 mt-4">
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i data-feather="facebook" class="fea icon-sm fea-social"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i data-feather="instagram" class="fea icon-sm fea-social"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i data-feather="twitter" class="fea icon-sm fea-social"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i data-feather="linkedin" class="fea icon-sm fea-social"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i data-feather="github" class="fea icon-sm fea-social"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i data-feather="youtube" class="fea icon-sm fea-social"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i data-feather="gitlab" class="fea icon-sm fea-social"></i></a></li>
                                </ul><!--end icon-->
                            </div>
                        </div>
                    </div><!--end col-->

<div class="col-lg-8 col-12">
<?php if ($_COOKIE['msg']) { ?>
<div class="clearfix"></div>	
<div class="alert alert-success">
	<a href="#" class="close" data-dismiss="alert" onClick="$('.alert').hide('slow');">&times;</a>
	<?php print str_replace("+", " ", base64_decode($_COOKIE['msg'])); ?>
</div>
<?php } ?>

<form name="login-form" id="login-form" method="post" action="" enctype="multipart/form-data">
<input class="form-control input-box" type="hidden" name="stage" value="1">
<input type="hidden" name="csrf" value="<?php print $_SESSION["token"]; ?>">
<input type="hidden" name="recaptchaResponse" id="recaptchaResponse">

<div class="card border-0 rounded shadow">
<div class="card-body">
<h5 class="text-md-left text-center">Personal Detail :</h5>

<div class="mt-3 text-md-left text-center d-sm-flex">
<img src="<?php echo $pimage; ?>" class="avatar float-md-left avatar-medium rounded-circle shadow mr-md-4" alt="" id="change-profile-img">
<input type="hidden" name="T2" value="<?php print $profilepic; ?>" >	
<div class="mt-md-4 mt-3 mt-sm-0">
<input type="file" name="profilepic" id="profilepic" class="btn btn-danger btn-lg" accept="image/*" hidden>
	
<a href="javascript:void(0)" class="btn btn-primary mt-2" id="uploadpic">Change Picture</a>
<?php if($profilepic!=""){?>

<a href="?delid=<?php print $userid; ?>" onclick="return confirm('Are you sure to delete this photo?');" class="btn btn-outline-primary mt-2 ml-2">Delete</a>	
<?php } ?>
</div>
</div>
<div class="col-lg-12 ">
<span id="savehint" style="color:#ff0000"></span>
</div>


	<div class="row mt-4">
		<div class="col-md-6">
			<div class="form-group position-relative">
				<label>First Name</label>
				<i data-feather="user" class="fea icon-sm icons"></i>
				<input name="fname" id="fname" type="text" class="form-control pl-5" placeholder="First Name" value="<?php echo $fname; ?>" required>
			</div>
		</div><!--end col-->
		<div class="col-md-6">
			<div class="form-group position-relative">
				<label>Last Name</label>
				<i data-feather="user-check" class="fea icon-sm icons"></i>
				<input name="lname" id="lname" type="text" class="form-control pl-5" placeholder="Last Name" value="<?php echo $lname; ?>">
			</div>
		</div><!--end col-->
		<div class="col-md-6">
			<div class="form-group position-relative">
				<label>Your Email</label>
				<i data-feather="mail" class="fea icon-sm icons"></i>
				<input name="email" id="email" type="email" class="form-control pl-5" placeholder="Your email" value="<?php echo $email; ?>" required>
			</div> 
		</div><!--end col-->
		
		<div class="col-md-6">
			<div class="form-group position-relative">
				<label>Phone No. :</label>
				<i data-feather="phone" class="fea icon-sm icons"></i>
				<input name="phone" id="phone" type="number" class="form-control pl-5" placeholder="Phone" value="<?php echo $phone; ?>">
			</div>
		</div><!--end col-->

		<div class="col-md-6">
			<div class="form-group position-relative">
				<label>Website :</label>
				<i data-feather="globe" class="fea icon-sm icons"></i>
				<input name="external_link" id="external_link" type="url" class="form-control pl-5" placeholder="Url" value="<?php echo $external_link; ?>">
			</div>
		</div><!--end col-->
		
		
		<div class="col-md-6">
			<div class="form-group position-relative">
				<label>Occupation</label>
				<i data-feather="bookmark" class="fea icon-sm icons"></i>
				<input name="occupation" id="occupation" type="text" class="form-control pl-5" placeholder="Occupation" value="<?php echo $occupation; ?>">
			</div> 
		</div><!--end col-->
		<div class="col-lg-12">
			<div class="form-group position-relative">
				<label>Description</label>
				<i data-feather="message-circle" class="fea icon-sm icons"></i>
				<textarea name="description" id="description" rows="4" class="form-control pl-5" placeholder="Description"><?php echo $description; ?></textarea>
			</div>
		</div>
	</div><!--end row-->
	<div class="row">
		<div class="col-sm-12">
			<input type="submit" id="submit" name="send" class="btn btn-primary" value="Save Changes">
		</div><!--end col-->
	</div><!--end row-->
<!--end form-->

                                
                            </div>
                        </div>
                    </div><!--end col--></form>
                </div><!--end row-->
            </div><!--end container-->
        </section><!--end section-->
        <!-- Profile Setting End -->

        
		<footer class="footer footer-bar">
		<div class="container text-center">
		<div class="row align-items-center">
		<div class="col-sm-6">
		<div class="text-sm-left">
		<p class="mb-0">© 2021 All rights reserved.</p>


		</div>
		</div><!--end col-->
		</div><!--end row-->
		</div><!--end container-->
		</footer><!--end footer-->
        <!-- Footer End -->

        <!-- Back to top -->
        <a href="#" class="btn btn-icon btn-soft-primary back-to-top"><i data-feather="arrow-up" class="icons"></i></a>
        <!-- Back to top -->

                <!-- javascript -->
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/jquery.easing.min.js"></script>
        <!-- Icons -->
        <script src="js/feather.min.js"></script>
        <!-- Main Js -->
        <script src="js/app.js"></script>
		
		
<script>
$(document).ready(function(){
	document.getElementById('uploadpic').addEventListener('click', openDialog);
});


function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#change-profile-img').attr('src', e.target.result);
	  //$("#uploadpic").html("Save Photo");
	  $("#savehint").html("<br/>Click on save changes below to save your profile picture.");
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$("#profilepic").change(function() {
  readURL(this);
});

function openDialog() {
  document.getElementById('profilepic').click();
}

</script>

<script src="../js/jquery.colorbox-min.js"></script>
<link rel="stylesheet" href="../css/colorbox.css" />
<script type="text/javascript">
$('document').ready(function(){	
		$(".download1").colorbox({iframe:true, width:"490px", height:"330px"});
});
</script>
    </body>
</html>