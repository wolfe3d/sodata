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
	<script src="js/jquery-3.6.0.min.js"></script>
	<!--
	<script src="js/jquery.validate.min.js"></script>
	<script src="js/additional-methods.min.js"></script> <!--Additional Methods are also for jquery validate-->

	<!--<script src="js/jquery.modal.min.js"></script> Modal plugin https://github.com/kylefox/jquery-modal-->
	<link rel="stylesheet" href="css/wolfestyle.css" />

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

	<script src="data.js"></script>
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
	<nav class="navbar navbar-expand-lg bg-light">
		<div class="container-fluid">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="#"><img style="vertical-align: middle " height="40px" src="images/waltoncthulu256.png"></img> Walton Science Olympiad</a>

			<div class="collapse navbar-collapse" id="navbarContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link active" id="mainBtn" aria-current="page" href="#home">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="eventBtn" href="#events">Events</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="tournamentBtn" href="#tournaments">Tournaments</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="studentBtn" href="#students">Teammates</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="leaderBtn" href="#leaders">Leaders</a>
					</li>
					<?php if (userHasPrivilege(4)){?>
						<li class="nav-item">
							<a class="nav-link" id="userBtn" href="#users">Users</a>
						</li>
					<?php }?>
				</ul>
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" id="loginBtn" href="logout.php">Logout</a>
					</li>
					<li style="display: inline-block; height: 100%; vertical-align: middle;"><img style="vertical-align: middle " height="40px" src="<?=$_SESSION['userData']['picture']?>" /></li>
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


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

</body>
</html>
