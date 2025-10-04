<?php
require_once ("php/functions.php");
//Check to make sure user is logged in and has privileges

// Include Google API client library
require_once 'google-api-php-client/vendor/autoload.php';

// Call Google API
$gClient = new Google_Client();
$gClient->setClientId(GOOGLE_CLIENT_ID);
$gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
$gClient->setRedirectUri(GOOGLE_REDIRECT_URL);
$gClient->addScope(['email', 'profile']);

//Check to make sure google is logged in and set variables
function checkGoogle($gpUserProfile)
{
	global $mysqlConn;
	// Include User library file
	require_once 'user.php';
	// Initialize User class
	$user = new User($mysqlConn);

	// Getting user profile info
	$gpUserData = array();
	$gpUserData['oauth_uid']  = !empty($gpUserProfile['id'])?$gpUserProfile['id']:'';
	$gpUserData['first_name'] = !empty($gpUserProfile['given_name'])?$gpUserProfile['given_name']:'';
	$gpUserData['last_name']  = !empty($gpUserProfile['family_name'])?$gpUserProfile['family_name']:'';
	$gpUserData['email'] = !empty($gpUserProfile['email'])?$gpUserProfile['email']:'';
	$gpUserData['gender'] = !empty($gpUserProfile['gender'])?$gpUserProfile['gender']:'';
	$gpUserData['locale'] = !empty($gpUserProfile['locale'])?$gpUserProfile['locale']:'';
	$gpUserData['picture'] = !empty($gpUserProfile['picture'])?$gpUserProfile['picture']:'';

	// Insert or update user data to the database
	$gpUserData['oauth_provider'] = 'google';
	$userData = $user->checkUser($gpUserData);
	// Storing user data in the session
	$_SESSION['userData'] = $userData;
}

//$gClient->setScopes(array('https://www.googleapis.com/auth/plus.me', 'https://www.googleapis.com/auth/moderator'));

if (isset($_GET['code'])) {
	try {
		$gClient->authenticate($_GET['code']);
		$_SESSION['token'] = $gClient->getAccessToken();
		header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
	} catch (Exception $exception) {
		if(userHasPrivilege(4))
		{
			echo "wolfe-catch enabled: (line 26 of data.php):";
			print_r($exception->getMessage());
		}
	}
} else {
	//echo "Wolfe - problem checking code";
	//$authUrl = $client->createAuthUrl();
	// header('Location: index.php#login');
}

try {
	if(isset($_SESSION['token'])){
		//echo "token set:".print_r($_SESSION['token']);
		$gClient->setAccessToken($_SESSION['token']);
	}
	$google_oauth =new Google_Service_Oauth2($gClient);
	$gpUserProfile = $google_oauth->userinfo->get(); //this is the line causing the following date_get_last_errors
	if(!isset($impersonate))
	{
		checkGoogle($gpUserProfile,$mysqlConn);
	}

} catch (Exception $exception) {
	if(userHasPrivilege(4))
	{
		echo "wolfe-catch enabled (line 48 of data.php):";
		print_r($exception->getMessage());
	}
	// Destroy entire session data
	header("Location:logout.php");
}

if(empty($gpUserProfile['id']))
{
	//header("Location:logout.php");
	echo "Your google user is invalid.  Check your setup...Wolfe!";
}

function loginoutBtn()
{
	if($_SESSION['token']){
		echo "<li><a id='loginBtn' href='#logout'>Logout</a></li>";
	}
	else{
		echo "<li><a id='loginBtn' href='#login'>Login</a></li>";
	}
}

userCheckPrivilege(1);
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Walton Science Olympiad</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="Walton Science Olympiad Homepage" />
	<meta name="keywords" content="Walton, Science, Olympiad, SO" />
	<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	
	<!--<script src="js/jquery.modal.min.js"></script> Modal plugin https://github.com/kylefox/jquery-modal-->
	<link rel="stylesheet" href="css/wolfestyle.css" />

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

	<script src="js/data.js"></script>
	<script src="js/teampropose.js"></script>

	<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

	<!-- Add the slick-theme.css if you want default styling -->
	<!-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>-->
	<!-- Add the slick-theme.css if you want default styling -->
	<!-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>-->

	<link rel="shortcut icon" href="images/waltoncthulu32.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<?php if (isset($impersonate)){?>
<body id="top">
	<div class="alert alert-warning alert-dismissible fade show" role="alert">
		<strong>Impersonation Mode!</strong> This allows you to debug user problems.
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="location.href='data.php#users'"></button>
	</div>
<?php }?>
	<!-- Header -->

	<!-- Navbar content -->
	<nav class="navbar navbar-expand-xxl bg-light shadow-sm mb-2">
		<div class="container-fluid py-2" style="position: relative;">
			<button class="navbar-toggler mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<!-- Desktop Title (Centered) -->
			<div class='d-none d-md-block'>
				<a class="navbar-brand d-flex align-items-center"
					href="#"
					style="
						position: absolute;
						left: 0; right: 0;
						margin-left: auto; margin-right: auto;
						
						top: 0.1rem;

						gap: 12px;
						justify-content: center;
						pointer-events: none;
					">
					<img style="vertical-align: middle;" height="50px" src="images/waltoncthulu256.png" alt="Logo" />
					<span style="color:#555f66; font-size: 1.5rem; font-weight: bold; line-height: 1;">
						WALTON SCIENCE OLYMPIAD
					</span>
				</a>
			</div>

			<!-- Mobile Title (Right-aligned and fixed at the top) -->
			<div class='d-md-none'>
				<a class="navbar-brand d-flex align-items-center"
				href="#"
				style="
						position: absolute;
						right: 0.3rem;
						top: 0.5rem;
						gap: 12px;
						pointer-events: none;
				">
					<img style="vertical-align: middle;" height="25px" src="images/waltoncthulu256.png" alt="Logo" />
					<span style="color:#555f66; font-size: 1.2rem; font-weight: bold; line-height: 1;">
						WALTON SCIENCE OLYMPIAD
					</span>
				</a>
			</div>

			<div class="collapse navbar-collapse" id="navbarContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0 bg-body rounded-3 p-2 shadow-sm fw-semibold">
					<li class="nav-item">
						<a class="nav-link active" style="font-size:0.9rem; font-weight: bold;" id="mainBtn" aria-current="page" href="#home">HOME</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" style="font-size:0.9rem; font-weight: bold;" id="eventBtn" href="#events">EVENTS</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" style="font-size:0.9rem; font-weight: bold;" id="tournamentBtn" href="#tournaments">TOURNAMENTS</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" style="font-size:0.9rem; font-weight: bold;" id="studentBtn" href="#students">TEAMMATES</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" style="font-size:0.9rem; font-weight: bold;" id="leaderBtn" href="#leaders">LEADERS</a>
					</li>
					<?php if (userHasPrivilege(4)){?>
						<li class="nav-item">
							<a class="nav-link" style="font-size:0.9rem; font-weight: bold;" id="userBtn" href="#users">USERS</a>
						</li>
					<?php }?>
				</ul>
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link fs-5 fw-semibold" id="loginBtn" href="logout.php">LOGOUT</a>
					</li>
					<?php
						if(!empty($_SESSION['userData']))
						{
							$studentID = getStudentID($_SESSION['userData']['userID']);
						}
						$output = "";
						$output .= 
						"<li style='display: inline-block; height: 100%; vertical-align: middle;'>
							<a href='#student-details-".$studentID."'>
								<img style='vertical-align: middle' height='50px' src='".$_SESSION['userData']['picture']."' />
							</a>
						</li>";
						echo $output;
					?>
				</ul>
			</div>
		</div>
	</nav>

	<!-- One -->
	<section id="main" class="wrapper style1">
		<div class="container-fluid">
			<h1 id="mainHeader">Home</a></h1>
			<div id="mainContainer">
				<div class="row">
					<div class="12u">
						Nothing has been loaded yet.
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Footer -->
	<footer class="text-center text-lg-start bg-light text-muted">

		<!-- Copyright -->
		<div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
			&copy;
			<a class="text-reset fw-bold" href="http://www.wolfescience.com">Wolfescience</a> All rights reserved.
		</div>
		<!-- Copyright -->
	</footer>
	<!-- Footer -->

	<!-- Modal -->
	<div class="modal" id="modalWait" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
        <img src="images/icanwait.gif" alt="I can wait...">
        </div>
    </div>
	</div>   
	<!-- Modal -->

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
	<!--<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>!-->

</body>
</html>
