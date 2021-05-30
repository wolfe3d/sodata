<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

//If user is logged in to this site, then the following redirects the user to the database program.
//if(userHasPrivilege(1))
//{
//	header("Location:data.php");
//	exit;
//}

if (isset($_SESSION['token'])) {
	try {
		header("Location:data.php#user");
	} catch (Exception $exception) {
			print_r($exception->getMessage());
	}
}
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
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<link rel="shortcut icon" href="images/waltonSO3.png">

		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
		<script type="text/javascript">
		$().ready(function() {
			//myData is a json object type
			/*var loginUrl = "login.php";
			if($("#loginBtn").text() == "Logout"){
				loginUrl = "logout.php";
			}*/
			checkPage();
			$(window).on('hashchange', function() {
				checkPage();
			});
		});
		function loginout()
		{
			var request = $.ajax({
			 url: "login.php",
			 cache: false,
			 method: "POST",
			 dataType: "html"
			});
			request.done(function( html ) {
			 //$("label[for='" + field + "']").append(html);
			 $("#loginPage").html(html);
			 $( "#login").show("fast");
			});

			request.fail(function( jqXHR, textStatus ) {
			 $("#loginPage").html("Search Error");
			});
		}
		function checkPage(){
			var myHash = location.hash.substr(1);
			$("section:not(#banner)").hide();
			if(myHash)
			{
				if(myHash=="login")
				{
					loginout();
				}
				else {
					$( "#"+myHash ).show( "slow", function() {
								// Animation complete.
					});
				}
			}
			else
			{
				$( "#main" ).show( "slow", function() {	});
			}
		}
	</script>
	</head>
	<body id="top">
		<!-- Header -->
			<header id="header" class="skel-layers-fixed">
				<h1><a href="#">Walton Science Olympiad</a></h1>

				<nav id="nav">
					<ul>
						<li><a id="mainBtn" href="#main">Home</a></li>
						<li><a id="tournamentBtn" href="#tournaments">Tournaments</a></li>
						<li><a id="summercampBtn" href="#summercamp">Summer Camp</a></li>
						<li><a id="contactBtn" href="#contact">Contact</a></li>
						<li><a id='loginBtn' href='#login'>Login</a></li>
						<li><a id="supportBtn"href="#support" class="button special">Support Us</a></li>
					</ul>
				</nav>
			</header>

		<!-- Banner -->
			<section id="banner">
				<div class="inner">
					<h2>Walton Science Olympiad</h2>
					<p>Walton High School</p>
				</div>
			</section>

		<!-- One -->
			<section id="main" class="wrapper style1">
				<header class="major">
					<h2>The Team</h2>
				</header>
				<div class="container">
					<div class="row">
						<div class="12u">
The Walton Science Olympiad team has been competing each year
since 1994. For more information about our team, please send an email to <a href="mailto:waltonscienceolympiad@gmail.com">waltonscienceolympiad@gmail.com</a>.
						</div>
					</div>
				</div>
			</section>

		<!-- Two -->
			<section id="tournaments" class="wrapper style2">
				<header class="major">
					<h2>Tournaments</h2>
				</header>
				<div class="container">
					<div class="row">
						<div class="6u">
<h3>Dodgen Walton Division B Science Olympiad Tournament</h3>

<div><a href="https://sites.google.com/site/dodgensciolyinvitationalreg/home">More Information Here</a></div>
<div>Tournaments were held at Walton 2018 and 2019.</div>

						</div>
						<div class="6u">
<h3>2018 Walton/GSMST Division C Science Olympiad Tournament</h3>

<div><a href="2018Ctournamentresults.html">Tournament Results Updated 11/26/18</a></div>

<div>This is the final update to the tournament results, including full rankings for Forensics and Protein Modeling.</div>

						</div>
					</div>
				</div>
			</section>

		<!-- Summer Camp -->
			<section id="summercamp" class="wrapper style1">
				<div class="container">
					<div class="row">
						<div class="12u">
							<h2>STEM Summer Camps</h2>
							<p>Science Olympiad coaches run the science camp at Walton.  Participants learn through engaging activities.  Walton students gain experience as mentors and money raised helps the Science Olympiad team.</p>
<p>For more information, go to the <a href="https://www.waltonstem.com/summer-camp">Walton STEM</a> site.</p>
						</div>
					</div>
				</div>
			</section>

		<!-- Contact -->
			<section id="contact" class="wrapper style2">
				<div class="container">
					<div class="row">
						<div class="12u">
							<h2>Contact</h2>
							<h3>Coaches:</h3><p>Wes Taylor & Doug Wolfe</p>
							<h3>Email:</h3><p><a href="mailto:waltonscienceolympiad@gmail.com">waltonscienceolympiad@gmail.com</a></p>

						</div>
					</div>
				</div>
			</section>

		<!-- Support -->
			<section id="support" class="wrapper style1">
				<div class="container">
					<div class="row">
						<div class="12u">
							<h2>Support</h2>
							<p>If you are would like to sponsor the team or donate money, please contact <a href="mailto:waltonscienceolympiad@gmail.com">waltonscienceolympiad@gmail.com</a>.</p>
						</div>
					</div>
				</div>
			</section>

			<!-- Support -->
				<section id="login" class="wrapper style1">
					<div class="container">
						<div class="row">
							<div class="12u">
								<h2>Login</h2>
								<div id="loginPage">Login to database</div>
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
