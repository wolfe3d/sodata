<?php
require_once ("../connectsodb.php");
require_once ("checksession.php");
require_once ("functions.php");
 //Check to make sure user is logged in and has privileges

 // Include Google API client library
 require_once 'google-api-php-client/vendor/autoload.php';

 // Call Google API
 $gClient = new Google_Client();
 $gClient->setClientId(GOOGLE_CLIENT_ID);
 $gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
 $gClient->setRedirectUri(GOOGLE_REDIRECT_URL);
 $gClient->addScope(['email', 'profile']);
 //$gClient->setScopes(array('https://www.googleapis.com/auth/plus.me', 'https://www.googleapis.com/auth/moderator'));

 if (isset($_GET['code'])) {
	 try {
		 $gClient->authenticate($_GET['code']);
		 $_SESSION['token'] = $gClient->getAccessToken();
		 header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
	 } catch (Exception $exception) {
		 if(userHasPrivilege(3))
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
	checkGoogle($gpUserProfile,$mysqlConn);
} catch (Exception $exception) {
	if(userHasPrivilege(3))
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
		<script src="js/jquery-3.6.0.min.js"></script>
		<script src="js/jquery.validate.min.js"></script>
		<script src="js/additional-methods.min.js"></script> <!--Additional Methods are also for jquery validate-->
		<!--<script src="js/jquery.modal.min.js"></script> <!--Modal plugin https://github.com/kylefox/jquery-modal-->
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<script src="data.js"></script>
		<link rel="shortcut icon" href="images/waltoncthulu32.png">
		<!--<link rel="stylesheet" href="css/jquery.modal.min.css" /> <!--Modal plugin -->
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
	</head>
	<body id="top">
		<!-- Header -->
			<header id="header" class="skel-layers-fixed">
				<h1 style="display: inline-block; height: 100%; vertical-align: middle;"><img style="vertical-align: middle " height="40px" src="images/waltoncthulu256.png"></img> <a href="#">Walton Science Olympiad</a></h1>

				<nav id="nav">
					<ul>
						<li><a id="mainBtn" href="#user">myHome</a></li>
						<li><a id="eventBtn" href="#events">Events</a></li>
						<li><a id="tournamentBtn" href="#tournaments">Tournaments</a></li>
						<li><a id="studentBtn" href="#students">Teammates</a></li>
						<li><a id="officerBtn" href="#officers">Officers & Event Leaders</a></li>
						<li><a id="coachBtn" href="#coaches">Coaches</a></li>
						<li><a id='loginBtn' href='logout.php'>Logout</a></li>
						<li style="display: inline-block; height: 100%; vertical-align: middle;"><img style="vertical-align: middle " height="40px" src="<?=$_SESSION['userData']['picture']?>" /></li>
					</ul>
				</nav>
			</header>

		<!-- One -->
			<section id="main" class="wrapper style1">
				<header class="major">
					<div style="display: inline-block; height: 100%; vertical-align: middle;"><img style="vertical-align: middle " height="256px" src="images/waltoncthulu256.png"></img></div>
					<h2 id="mainHeader"><a href="#">Home</a></h2>

				</header>
				<div class="container" id="mainContainer">
					<div class="row">
						<div class="12u">
								Nothing has been loaded yet.
						</div>
					</div>
				</div>
			</section>

		<!-- Footer -->
			<footer id="footer">
				<div class="container">
					<ul class="copyright">
						<li>&copy; Wolfescience. All rights reserved.</li>
						<li>Design: <a href="http://www.wolfescience.com">Wolfescience</a></li>
					</ul>
				</div>
			</footer>

	</body>
</html>
