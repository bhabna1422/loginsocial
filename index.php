<?php
include("includes/connection.php");
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
<!-- Google Code-->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-42340196-1"></script>
<script>
var host = window.location.hostname;
if(host != "localhost")
{
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-42340196-1');
}
</script>
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
<a  href="../buyauthregister.php?page=a2z-login-register" target="_blank" class="btn btn-primary download1">Buy Now</a>
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
<?php
if (isset($_SESSION['user_id'])){
?>
<!--
<li><a href="user-dashboard">Welcome <?php /* echo $_SESSION['fname']; */?></a></li>
-->
<li><a href="user-dashboard">Dashboard</a></li>
<li><a href="logout">Logout</a></li>
<?php }else{ ?>
<li class="has-submenu">
<a href="javascript:void(0)">Login/Register</a><span class="menu-arrow"></span>
<ul class="submenu">                    
	<li><a href="auth-login.php">Login</a></li>
	<li><a href="auth-cover-login.php">Login Cover</a></li>
	<li><a href="auth-login-three.php">Login Simple</a></li>
	<li><a href="auth-signup.php">Signup</a></li>
	<li><a href="auth-cover-signup.php">Signup Cover</a></li>
	<li><a href="auth-signup-three.php">Signup Simple</a></li>
	<li><a href="auth-re-password.php">Reset Password</a></li>
	<li><a href="auth-cover-re-password.php">Reset Password Cover</a></li>
	<li><a href="auth-re-password-three.php">Reset Password Simple</a></li>
</ul>
</li>
<?php } ?>

<li><a href="documentation/index.html" target="_blank">Documentation</a></li>
                    </ul><!--end navigation menu-->
                    <div class="buy-menu-btn d-none">
                        <a href="../buyauthregister.php?page=a2z-login-register" target="_blank" class="btn btn-primary download1">Buy Now</a>
                    </div><!--end login button-->
                </div><!--end navigation-->
            </div><!--end container-->
        </header><!--end header-->

        
        <!-- Hero Start -->
        <section class="bg-half-170  d-table w-100" id="home">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-md-7">
                        <div class="title-heading mt-4">
                            <h1 class="heading mb-3">Secure-PHP-Login &   <span class="text-primary">Registration System</span> </h1>
                            <p class="para-desc text-muted">Create Secure Login and Registration System with Email Verification using HTML CSS Bootstrap PHP and MYSQL. Including login/register with Social Media like Facebook, Gmail and Twitter.</p>
                            <div class="mt-4">
                                <a href="auth-login" class="btn btn-primary mt-2 mr-2"><i data-feather="play" class="fea icon-ex-md"></i> Get Started</a>
                                <a href="documentation/index.html" target="_blank" class="btn btn-outline-primary mt-2"><i data-feather="book-open" class="fea icon-ex-md"></i> Documentation</a>
                            </div>
                        </div>
                    </div><!--end col-->

                    <div class="col-lg-5 col-md-5 mt-4 pt-2 mt-sm-0 pt-sm-0">
                        <img src="images/login_right.png" alt="">
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </section><!--end section-->
        <!-- Hero End -->

        <!-- How It Work Start -->
        <section class="section bg-light border-bottom">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <div class="section-title mb-4 pb-2">
                            <h4 class="title mb-4">What is Inside?</h4>
                            <p class="text-muted para-desc mb-0 mx-auto">You will get <span class="text-primary font-weight-bold">3 different design layout</span> for login registration system, along with email verification. Social media login registration. Change password, edit profile and more...</p>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
				
				<div class="row">
                    <div class="col-md-4 col-12 mt-5">
                        <div class="features text-center">
                            <div class="image position-relative d-inline-block">
                                <img src="images/user.svg" class="avatar avatar-small" alt="">
                            </div>

                            <div class="content mt-4">
                                <h4 class="title-2">Login & Register</h4>
                                <p class="text-muted mb-0">Login register with <span class="text-primary font-weight-bold">Social Media</span> account or register with email verification.</p>
                            </div>
                        </div>
                    </div><!--end col-->
                    
                    <div class="col-md-4 col-12 mt-5">
                        <div class="features text-center">
                            <div class="image position-relative d-inline-block">
                                <img src="images/video.svg" class="avatar avatar-small" alt="">
                            </div>

                            <div class="content mt-4">
                                <h4 class="title-2">Edit Profile & Change Password</h4>
                                <p class="text-muted mb-0">Also includes edit profile, change password and forgot password / reset password.</p>
                            </div>
                        </div>
                    </div><!--end col-->
                    
                    <div class="col-md-4 col-12 mt-5">
                        <div class="features text-center">
                            <div class="image position-relative d-inline-block">
                                <img src="images/camera.svg" class="avatar avatar-small" alt="">
                            </div>

                            <div class="content mt-4">
                                <h4 class="title-2">Change Profile Picture</h4>
                                <p class="text-muted mb-0">Upload profile picture.</p>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
				

                <div class="row align-items-center">

				<div class="container mt-100">
                <div class="row">
					<div class="col-lg-4 col-md-6 mb-4 pb-2">
                        <div class="media key-feature align-items-center p-3 rounded shadow">
                            <div class="icon text-center rounded-circle mr-3">
							<i data-feather="user-check" class="fea icon-ex-md text-primary"></i>
                                
                            </div>
                            <div class="media-body">
                                <h4 class="title mb-0">Login via Facebook, Twitter or Gmail</h4>
                            </div>
                        </div>
                    </div><!--end col-->
					
					<div class="col-lg-4 col-md-6 mb-4 pb-2">
                        <div class="media key-feature align-items-center p-3 rounded shadow">
                            <div class="icon text-center rounded-circle mr-3">
                              <i data-feather="mail" class="fea icon-ex-md text-primary"></i>   
                            </div>
                            <div class="media-body">
                                <h4 class="title mb-0">Send Emails Using PHP mail() or SMTP</h4>
                            </div>
                        </div>
                    </div><!--end col-->
                    
                    <div class="col-lg-4 col-md-6 mb-4 pb-2">
                        <div class="media key-feature align-items-center p-3 rounded shadow">
                            <div class="icon text-center rounded-circle mr-3">
							<i data-feather="user" class="fea icon-ex-md text-primary"></i>
                               
                            </div>
                            <div class="media-body">
                                <h4 class="title mb-0">User Profile Update</h4>
                            </div>
                        </div>
                    </div><!--end col-->
					
					<div class="col-lg-4 col-md-6 mb-4 pb-2">
                        <div class="media key-feature align-items-center p-3 rounded shadow">
                            <div class="icon text-center rounded-circle mr-3">
                                <i data-feather="mail" class="fea icon-ex-md text-primary"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="title mb-0">Email Verification</h4>
                            </div>
                        </div>
                    </div><!--end col-->
					
					<div class="col-lg-4 col-md-6 mb-4 pb-2">
                        <div class="media key-feature align-items-center p-3 rounded shadow">
                            <div class="icon text-center rounded-circle mr-3">
                                <i data-feather="key" class="fea icon-ex-md text-primary"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="title mb-0">Forgot Password</h4>
                            </div>
                        </div>
                    </div><!--end col-->
					

					
					<div class="col-lg-4 col-md-6 mb-4 pb-2">
                        <div class="media key-feature align-items-center p-3 rounded shadow">
                            <div class="icon text-center rounded-circle mr-3">
                                <i data-feather="code" class="fea icon-ex-md text-primary"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="title mb-0">Built With Core PHP</h4>
                            </div>
                        </div>
                    </div><!--end col-->
                    
                    <div class="col-lg-4 col-md-6 mb-4 pb-2">
                        <div class="media key-feature align-items-center p-3 rounded shadow">
                            <div class="icon text-center rounded-circle mr-3">
                                <i data-feather="database" class="fea icon-ex-md text-primary"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="title mb-0">MySQL Database</h4>
                            </div>
                        </div>
                    </div><!--end col-->
					
					<div class="col-lg-4 col-md-6 mb-4 pb-2">
                        <div class="media key-feature align-items-center p-3 rounded shadow">
                            <div class="icon text-center rounded-circle mr-3">
                                <i data-feather="code" class="fea icon-ex-md text-primary"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="title mb-0">Based On Bootstrap 4</h4>
                            </div>
                        </div>
                    </div><!--end col-->
					
                    <div class="col-lg-4 col-md-6 mb-4 pb-2">
                        <div class="media key-feature align-items-center p-3 rounded shadow">
                            <div class="icon text-center rounded-circle mr-3">
                                <i data-feather="monitor" class="fea icon-ex-md text-primary"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="title mb-0">Fully Responsive</h4>
                            </div>
                        </div>
                    </div><!--end col-->
                    
                    <div class="col-lg-4 col-md-6 mb-4 pb-2">
                        <div class="media key-feature align-items-center p-3 rounded shadow">
                            <div class="icon text-center rounded-circle mr-3">
                                <i data-feather="heart" class="fea icon-ex-md text-primary"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="title mb-0">Browser Compatibility</h4>
                            </div>
                        </div>
                    </div><!--end col-->
                    
                    <div class="col-lg-4 col-md-6 mb-4 pb-2">
                        <div class="media key-feature align-items-center p-3 rounded shadow">
                            <div class="icon text-center rounded-circle mr-3">
                                <i data-feather="eye" class="fea icon-ex-md text-primary"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="title mb-0">Retina Ready</h4>
                            </div>
                        </div>
                    </div><!--end col-->
                    
                    <div class="col-lg-4 col-md-6 mb-4 pb-2">
                        <div class="media key-feature align-items-center p-3 rounded shadow">
                            <div class="icon text-center rounded-circle mr-3">
                                <i data-feather="edit" class="fea icon-ex-md text-primary"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="title mb-0">Easy to Customize</h4>
                            </div>
                        </div>
                    </div><!--end col-->
                    
                    <div class="col-12 text-center">
                        <a href="../buyauthregister.php?page=a2z-login-register" class="btn btn-primary download1">Buy Now <i class="mdi mdi-arrow-right"></i></a>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->

                </div><!--end row-->
            </div><!--end container-->

        </section><!--end section-->
        <!-- How It Work End -->

        <footer class="footer footer-bar">
            <div class="container text-center">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="text-sm-left">
<p class="mb-0">© 2021 All rights reserved.</p>
<a href='https://www.freepik.com/vectors/blue' style="font-size:10px;">Blue vector created by vectorjuice - www.freepik.com</a>

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
		
		<script src="../js/jquery.colorbox-min.js"></script>
		<link rel="stylesheet" href="../css/colorbox.css" />
		<script type="text/javascript">
		$('document').ready(function(){	
				$(".download1").colorbox({iframe:true, width:"490px", height:"330px"});
		});
		</script>
    </body>
</html>