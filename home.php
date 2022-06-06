<?php
require_once("php/functions.php");
userCheckPrivilege(1);
$output = "";
$userID = $_SESSION['userData']['userID'];
$currentYear = getCurrentSOYear();
$fallRosterDate = strval(getCurrentSOYear()-1)."-08-01";
if(!empty($_SESSION['userData'])){
	$studentID = NULL;
	$coachID = NULL;
	if($_SESSION['userData']['type'] =='student')
	{
		$studentID = getStudentID($mysqlConn, $userID);
	}
	else if ($_SESSION['userData']['type'] )
	{
		$coachID = getCoachID($mysqlConn, $userID);
	}
	$output .= "<div style='display: flex; justify-content: center; vertical-align: middle;padding: 10px;'><img style='vertical-align: middle' height='256px' src='images/waltoncthulu256.png'></img></div>";
	$output .= '<div>';
	//$output .="<p style=' text-align: center'><img src='images/teamphoto.jpg' alt='team photo' width='600px'><p>";

	$output .= '<p style="text-align:center"><iframe src="https://docs.google.com/presentation/d/e/2PACX-1vStMRtaqu9vS_F3ih0aW8sfizoMhtexECHy2WdPEywYjVitnFgDnNsHxb8V-R2-XqFjZErmmH5e2Nx9/embed?start=false&loop=false&delayms=3000" frameborder="0" width="960" height="569" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe></p>';
		$output .= '<p>You are logged in to Walton Science Olympiad Team Website!</p>';
		$output .= '<img src="'.$_SESSION['userData']['picture'].'">';
		$output .= '<p><b>Name:</b> '.$_SESSION['userData']['first_name'].' '.$_SESSION['userData']['last_name'].'</p>';
		$output .= '<p><b>Email:</b> '.$_SESSION['userData']['email'].'</p>';

		$output .= "<h2> Quick Links </h2><ul>";
		$output .= "<li><a href='https://drive.google.com/file/d/13gIkPawogKlDHzhNBfTPgQ5hi045QDiv/view?usp=sharing'> 2022 Official Rules Manual </a></li>";
		$output .= "<li><a href='https://drive.google.com/drive/folders/17LMINQEqhEP3IQzT8jj1-3Iw6gt8boRI?usp=sharing'> Digital Test Bank </a></li>";
		$output .= "<li><a href='https://calendar.google.com/calendar/embed?src=waltonscienceclub%40gmail.com&ctz=America%2FNew_York'> Google Calendar </a></li>";
		if($studentID)
		{
			$output .= "<li><a href='https://scilympiad.com/public/Student/StudentDB'>Scilympiad</a> ID: ".studentScilympiadID($mysqlConn, $studentID)."</li>";
		}
		$output .= "</ul>";

		//Reminders
		//Show new tournaments signups with links to tournament pages, priority of events with links to events, previous tournament results.
		$tournament = "";
		if($studentID)
		{
			$tournament =	getUpcomingTournamentStudent($mysqlConn, $userID, $studentID);
		}
		else if($coachID){
			$tournament =	getUpcomingTournamentCoach($mysqlConn, $_SESSION['userData']['schoolID']);
		}
		$output .= $tournament;

		if($studentID)
		{
			$output .= "<hr><h2>My Events</h2>";
			//Get latest team assignments
			$output .= getLatestTeamTournamentStudent($mysqlConn, $studentID);
			//show student's event priority
			$output .= studentEventPriority($mysqlConn, $studentID);
			//show all previous results for this student
			$output .= studentTournamentResults($mysqlConn, $studentID, true);
		}
		//Coach Reminders

	}else{
		$output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
	}
	echo $output;
	?>
