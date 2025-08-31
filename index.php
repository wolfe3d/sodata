<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges

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
	<meta property="og:image" content="images/waltoncthulu1200.png" />

	<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<!--<script src="js/jquery.modal.min.js"></script> Modal plugin https://github.com/kylefox/jquery-modal-->
	<link rel="stylesheet" href="css/wolfestyle.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link active" style="font-size:0.9rem; font-weight: bold;" id="mainBtn" aria-current="page" href="#">HOME</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" style="font-size:0.9rem; font-weight: bold;" id="tournamentBtn" href="#tournaments">TOURNAMENTS</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" style="font-size:0.9rem; font-weight: bold;" id="summercampBtn" href="#summercamp">SUMMER CAMP</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" style="font-size:0.9rem; font-weight: bold;" id="boosterclubBtn" href="https://www.waltonsciolybooster.org/">PARENTS</a>
					</li>
				</ul>
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" style="font-size:0.9rem; font-weight: bold;" id="contactBtn" href="#contact">CONTACT</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" style="font-size:0.9rem; font-weight: bold;" id="supportBtn" href="#support">SUPPORT US</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" style="font-size:0.9rem; font-weight: bold;" id="loginBtn" href="#login">LOGIN</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<div id="banner" class="jumbotron jumbotron-fluid">
		<div><img style="vertical-align: top" height="256px" src="images/waltoncthulu1200white.png"></img></div>
		<h1 style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);" class="text-white fw-bold">Walton Science Olympiad</h1>
		<p style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);" class="text-white">Walton High School</p>
	</div>

	<!-- One -->
	<section id="main" class="wrapper style1">
		<header class="major">
			<h2>The Team</h2>
		</header>
		<div class="container">
			<div class="row">
				<div class="12u">
					<p>Walton Science Olympiad meets on Tuesdays at 3:30PM in Mr. Wolfe's classroom (Room 402).  If you have never been on a Science Olympiad team, go to <a href="https://www.soinc.org/info/about-science-olympiad">National Science Olympiad Website</a> to learn more. </p>
					<p>Usually, students at the end of each year and new students join after club fair.  If it is later than September 1, you may still be able to join, but you must contact the coaches using the email below.</p>
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
						<h3>Dodgen-Walton Division B (Middle School) Science Olympiad Tournament</h3>
						<h4>November 15, 2025</h4>
						<div><a href="https://scilympiad.com/dodgen-walton">Dodgen-Walton In Person</a></div>
						<div><a href="https://scilympiad.com/dw-satellite">DW Satellite</a></div>
						<br><br>
					</div>

					<hr>
					<br><br>
					<div class="6u">
						<h3>Previous Tournaments</h3>
						<div>Tournaments were held at Walton since 2017.  Previously, the Dodgen-Walton tournament was known as the Dodgen Invitational and Cobb Invitational.  This tournament was started by Paul and Kathy Jacobson at least 25 years ago and is currently directed by Doug Wolfe.</div>
						<br><br>
						<h4>2024 Dodgen-Walton Division B Tournament Results (2025 National Rules Year)</h4>
						<div><a href="https://www.duosmium.org/results/2024-11-16_dodgen_walton_satellite_invitational_b/">Satellite Scores</a></div>
						<div><a href="https://www.duosmium.org/results/2024-11-16_dodgen_walton_invitational_b/">In Person Combined (Filter by Division AA or A)</a></div>

						<h4>2023 Dodgen-Walton Division B Tournament Results (2024 National Rules Year)</h4>
						<div><a href="results/2022/Satellite_Results_2023.xlsx">Satellite Scores</a></div>
						<div><a href="https://www.duosmium.org/results/2023-11-11_dodgen_walton_invitational_b/">In Person (Combined)</a></div>
						<div><a href="results/2022/InPerson_GoldFlight_2023.pdf">In Person Gold Flight</a></div>
						<div><a href="results/2022/InPerson_SilverFlight_2023.pdf">In Person Silver Flight</a></div>

						<h4>2022 Dodgen-Walton Division B Tournament Results (2023 National Rules Year)</h4>
						<div><a href="results/2022/Results_Satellite_2022.xlsx">Satellite Scores</a></div>
						<div><a href="results/2022/Results_Gold_2022.pdf">In Person Gold Flight</a></div>
						<div><a href="results/2022/Results_Silver_2022.pdf">In Person Silver Flight</a></div>

						<h4>2021 Dodgen-Walton Division B Tournament Results (2022 National Rules Year)</h4>
						<div><a href="results/2021/RawScores_2021.xlsx">Raw Scores</a></div>
						<div><a href="results/2021/Results_2021.xlsx">Overall Scores</a></div>
						<div><a href="results/2021/Results_Gold_2021.xlsx">Gold Flight</a></div>
						<div><a href="results/2021/Results_Silver_2021.xlsx">Silver Flight</a></div>

						<h4>2020 Dodgen-Walton Division B Science Olympiad Tournament (2021 National Rules Year)</h4>
						
						<h4>2019 Dodgen-Walton Division B Science Olympiad Tournament (2020 National Rules Year)</h4>

						<h4>2018 Walton/GSMST Division C (High School) Science Olympiad Tournament (2019 National Rules Year)</h4>
						<div><a href="results/2018/2018Ctournamentresults.html">Tournament Results Updated 11/26/18</a></div>
						<div>This is the final update to the tournament results, including full rankings for Forensics and Protein Modeling.</div>
						
						<h4>2018 Dodgen-Walton Division B Science Olympiad Tournament (2019 National Rules Year)</h4>
						
						<h4>2017 Dodgen-Walton Division B Science Olympiad Tournament (2018 National Rules Year)</h4>
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
						<p><strong>Head Coach:</strong> Doug Wolfe</p>
						<p><strong>Assistant Coach:</strong> Matt Curtis</p>
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
						<h2>Support Us</h2>
						<p>Parents, please sign up to volunteer here <a href="
https://www.signupgenius.com/index.cfm?go=c.SignUpSearch&eid=08C7CEDDF5C8FF640F&cs=09CBBAD88FB88B117B0A640F5BB09BCAFDB0&sortby=startdate
">2024-2025 Signup Link</a>.  Thank you!</p>
						<p>If you are would like to sponsor or support the team, please contact the <a href="https://www.waltonsciolybooster.org/">Booster Club</a>.  Thank you!</p>
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

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

	</body>
	</html>
