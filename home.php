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

	$output .= getCarousel($mysqlConn, $_SESSION['userData']['schoolID']);
	if(userHasPrivilege(4))
	{
		$output .= "<p><a type='button' class='btn btn-primary' href='#home-edit'><span class='bi bi-edit'></span> Edit Carousel</button></div></a>";
	}
	$output .= '<p>You are logged in to Walton Science Olympiad Team Website!</p>';
	$output .= '<img src="'.$_SESSION['userData']['picture'].'">';
	$output .= '<p><b>Name:</b> '.$_SESSION['userData']['first_name'].' '.$_SESSION['userData']['last_name'].'</p>';
	$output .= '<p><b>Email:</b> '.$_SESSION['userData']['email'].'</p>';

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
		//Get latest team assignments
		$myEvents = getLatestTeamTournamentStudent($mysqlConn, $studentID);
		//show student's event priority
		$myEvents .= studentEventPriority($mysqlConn, $studentID);
		if($myEvents)
		{
			$output .= "<hr><h2>My Events</h2>" . $myEvents;
		}
		//show all previous results for this student
		$output .= studentTournamentResults($mysqlConn, $studentID, true);
	}
	//Coach Reminders

}else{
	$output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
}
echo $output;
?>
