<?php
require_once("php/functions.php");
userCheckPrivilege(1);
$output = "";
$userID = $_SESSION['userData']['userID'];

//get upcoming tournament Information for Students
function getUpcomingTournamentStudent($db, $userID, $studentID)
{
	$date = date('Y-m-d', time());
	$query = "SELECT `tournamentName`,`tournamentID`,`dateTournament`,`tournament`.`schoolID`
	FROM `student` INNER JOIN `tournament` ON `tournament`.`schoolID` = `student`.`schoolID`
	WHERE `studentID` = $studentID AND `dateTournament` >= '$date' AND `notCompetition` = 0
	ORDER BY `dateTournament`";
	//$query = "SELECT `tournamentName`,`tournament`.`tournamentID`,`dateTournament`,`teamName` FROM `student` INNER JOIN `teammate` ON `student`.`studentID`=`teammate`.`studentID` INNER JOIN `team` ON `teammate`.`teamID` = `team`.`teamID` INNER JOIN `tournament` ON `team`.`tournamentID` = `tournament`.`tournamentID` WHERE `userID` = $userID AND `dateTournament` >= '$date' AND `notCompetition` = 0 ORDER BY `dateTournament`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = '';
	if($result && mysqli_num_rows($result)>0)
	{
		$output = '<hr><h2>Upcoming Tournaments</h2>';
		while ($row = $result->fetch_assoc()):
			$output.="<div id=\"".$row['tournamentName']."\">";
			$output.="<h3>".$row['tournamentName']." - ".$row['dateTournament'] . "</h3>";
			$output.="<div><a class='btn btn-primary' role='button' href=\"#tournament-view-".$row['tournamentID']."\"><span class='bi bi-controller'></span> View Details</a></div>";
			$output.=studentTournamentSchedule($db, $row['tournamentID'], $studentID);
			$output.="</div>";
		endwhile;
	}
	return $output;
}
//get upcoming tournament Information for Coaches
function getUpcomingTournamentCoach($db, $schoolID)
{
	$date = date('Y-m-d', time());
	//fallRosterDate should be changed to a part of the table that indicated that this is a roster (not a tournament)
	$query = "SELECT `tournamentName`,`tournamentID`,`dateTournament` FROM `tournament`
	WHERE `schoolID` = $schoolID AND `dateTournament` >= '$date' AND `notCompetition` = 0
	ORDER BY `dateTournament`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = '';
	if($result && mysqli_num_rows($result)>0)
	{
		$output = '<h2>Upcoming Tournaments</h2><ul>';
		while ($row = $result->fetch_assoc()):
			$output.="<li id=\"".$row['tournamentName']."\">";
			$output.= "<a class='btn btn-primary btn-sm' role='button' href=\"#tournament-view-".$row['tournamentID']."\"><span class='bi bi-controller'></span> ".$row['tournamentName']."</a>";
			$output .= " - " . $row['dateTournament'];
			$output.="</li>";
		endwhile;
		$output .= '</ul>';
	}
	return $output;
}


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
	$output .= '<p>You are logged in to Walton Science Olympiad Team Website!</p>';

	$output .= "<div style='display: flex; justify-content: center; vertical-align: middle;padding: 10px;'><img style='vertical-align: middle' height='256px' src='images/waltoncthulu256.png'></img></div>";
	$output .= '<div>';
	//$output .="<p style=' text-align: center'><img src='images/teamphoto.jpg' alt='team photo' width='600px'><p>";

	$output .= getCarousel($mysqlConn, $_SESSION['userData']['schoolID']);
	if(userHasPrivilege(4))
	{
		$output .= "<p><a type='button' class='btn btn-primary' href='#home-edit'><span class='bi bi-edit'></span> Edit Carousel</button></a></p>";
	}
	$output .= getInfo($mysqlConn, $_SESSION['userData']['schoolID']);
	if(userHasPrivilege(4))
	{
		$output .= "<p><a type='button' class='btn btn-primary' href='#news-edit'><span class='bi bi-edit'></span> Edit News</button></a></p>";
	}

	$output .= '<img src="'.$_SESSION['userData']['picture'].'">';
	$output .= '<p><b>Name:</b> '.$_SESSION['userData']['first_name'].' '.$_SESSION['userData']['last_name'].'</p>';
	$output .= '<p><b>Email:</b> '.$_SESSION['userData']['email'].'</p>';

	if($studentID)
	{
		$output .= "<p><a href='https://scilympiad.com/public/Student/StudentDB'>Scilympiad</a> ID: ".studentScilympiadID($mysqlConn, $studentID)."</p>";
	$output.="<p><a class='btn btn-info' role='button' href='#student-details-$studentID'><span class='bi bi-file-earmark-person'></span> Your Information</a></p>";

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
		//$myEvents .= studentEventPriority($mysqlConn, $studentID);
		if($myEvents)
		{
			$output .= "<hr><h2>My Events</h2>" . $myEvents;
		}
		//show all previous results for this student
		$myTournamentResults = studentTournamentResults($mysqlConn, $studentID, true);
		if($myTournamentResults)
		{
			$output .= "<hr><h2>My Results</h2>" . $myTournamentResults;
		}

	}
	//Coach Reminders
	if(userHasPrivilege(5))
	{
		$output.="<p><a class='btn btn-info' role='button' href='#tournamentsscore'><span class='bi bi-graph-up'></span> Student Ranking</a></p>";
	}

}else{
	$output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
}
echo $output;
?>
