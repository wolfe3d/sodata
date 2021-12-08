<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

if (isset($_SESSION['token'])) {
	try {
		header("Location:data.php#home");
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
		<link rel="shortcut icon" href="images/waltoncthulu32.png">

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
				<h1 style="display: inline-block; height: 100%; vertical-align: middle;"><img style="vertical-align: middle " height="40px" src="images/waltoncthulu256.png"></img> <a href="#">Walton Science Olympiad</a></h1>

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
			<section id="banner" style="padding-top:30px;padding-bottom:30px;">
				<div class="inner">
					<div><img style="vertical-align: top" height="256px" src="images/waltoncthulu1200white.png"></img></div>
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
							<p>Walton Science Olympiad meets on Tuesdays at 3:30PM in Mr. Taylor's classroom (Room 414).  If you have never been on a Science Olympiad team, go to <a href="https://www.soinc.org/info/about-science-olympiad">National Science Olympiad Website</a> to learn more. </p>
							<p>If it is later than September 1, you may still be able to join, but you must contact the coaches using the email below.</p>
							<p>The Walton Science Olympiad team has been competing each year since 1994. Persistence and determination are what define us. We passionately study for our test events, and we enthusiastically design and collaborate for our build events. Teamwork is what unifies us.</p>
							<p>Here at Walton Science Olympiad, science is not the only focus in our program. Ingenuity, teamwork, and creativity are also integral components of the Science Olympiad team. The friendships and connections you forge with others over similar scientific interests will be meaningful and lasting. The new information you learn could even be applicable to your daily life. Walton Science Olympiad is much more than just pure science. It is about the curiosity that sparks motivation and the motivation that spurs ingenuity. It is about the bonding experience with your peers and your teachers. It is about collaborating with like-minds on subject topics you are passionate about while simultaneously learning about science.</p>
							<p>For more information about our team, please send an email to <a href="mailto:waltonscienceolympiad@gmail.com">waltonscienceolympiad@gmail.com</a>.<p>
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
<div><a href="https://scilympiad.com/dodgen-walton">Our Scilympiad site</a></div>
<div>Tournaments were held at Walton 2018, 2019, 2020 (remote), and 2021 (remote).</div>

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
								<div id="loginPage">Login to team website.  This has tournament and event information.</div>
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
