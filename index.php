<?php
require_once ("../connectsodb.php");
require_once  ("/php/checksession.php"); //Check to make sure user is logged in and has privileges

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
		<script src="js/jquery.validate.min.js"></script>
		<script src="js/additional-methods.min.js"></script> <!--Additional Methods are also for jquery validate-->
		<!--<script src="js/jquery.modal.min.js"></script> Modal plugin https://github.com/kylefox/jquery-modal-->
		<link rel="stylesheet" href="css/wolfestyle.css" />

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

		<script src="data.js"></script>
		<link rel="shortcut icon" href="images/waltoncthulu32.png">
		<meta name="viewport" content="width=device-width, initial-scale=1">
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
					$( "#"+myHash ).show();
				}
			}
			else
			{
				$( "#main" ).show("fast");
			}
		}
	</script>
	</head>
	<body id="top">
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
									<a class="nav-link active" id="mainBtn" aria-current="page" href="#">Home</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="tournamentBtn" href="#tournaments">Tournaments</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="summercampBtn" href="#summercamp">Summer Camp</a>
								</li>
							</ul>
							<ul class="navbar-nav">
								<li class="nav-item">
									<a class="nav-link" id="contactBtn" href="#contact">Contact</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="supportBtn" href="#support">Support Us</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="loginBtn" href="#login">Login</a>
								</li>
							</ul>
						</div>
					</div>
				</nav>

				<div id="banner" class="jumbotron jumbotron-fluid">
						<div><img style="vertical-align: top" height="256px" src="images/waltoncthulu1200white.png"></img></div>
						<h1>Walton Science Olympiad</h1>
						<p>Walton High School</p>
				</div>

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
