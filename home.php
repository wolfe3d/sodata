<?php
require_once("php/functions.php");
userCheckPrivilege(1);
$output = "";
$userID = $_SESSION['userData']['userID'];

//get upcoming tournament Information for Students
function getUpcomingTournamentStudent($userID, $studentID)
{
	global $mysqlConn;
	$date = date('Ymd', time());
	$query = "SELECT `tournamentName`,`tournamentID`,`dateTournament`,`tournament`.`schoolID`, `tournament`.`year`
	FROM `student` INNER JOIN `tournament` ON `tournament`.`schoolID` = `student`.`schoolID`
	WHERE `studentID` = $studentID AND `dateTournament` >= '$date' AND `notCompetition` = 0
	ORDER BY `dateTournament`";
	//$query = "SELECT `tournamentName`,`tournament`.`tournamentID`,`dateTournament`,`teamName` FROM `student` INNER JOIN `teammate` ON `student`.`studentID`=`teammate`.`studentID` INNER JOIN `team` ON `teammate`.`teamID` = `team`.`teamID` INNER JOIN `tournament` ON `team`.`tournamentID` = `tournament`.`tournamentID` WHERE `userID` = $userID AND `dateTournament` >= '$date' AND `notCompetition` = 0 ORDER BY `dateTournament`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = '';
	if($result && mysqli_num_rows($result)>0)
	{
		$output = '<hr><h2>Upcoming Tournaments</h2>';
		while ($row = $result->fetch_assoc()):
			$output.="<div id=\"".$row['tournamentName']."\">";
			$output.="<h3>".$row['tournamentName']." - ".$row['dateTournament'] . "</h3>";
			$output.="<div><a class='btn btn-primary' role='button' href=\"#tournament-view-".$row['tournamentID']."\"><span class='bi bi-controller'></span> View Details</a></div>";
			$output.=studentTournamentSchedule($row['tournamentID'], $studentID, "", $row['year']);
			$output.="</div>";
		endwhile;
	}
	return $output;
}
//get upcoming tournament Information for Coaches
function getUpcomingTournamentCoach()
{
	global $mysqlConn, $schoolID;
	$date = date('Ymd', time());
	//fallRosterDate should be changed to a part of the table that indicated that this is a roster (not a tournament)
	$query = "SELECT `tournamentName`,`tournamentID`,`dateTournament` FROM `tournament`
	WHERE `schoolID` = $schoolID AND `dateTournament` >= '$date' ORDER BY `dateTournament`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
// Get all meetings from an event
function getEventMeetings($eventID)
{
	global $mysqlConn;
	$query = "SELECT * FROM `meeting` WHERE `meeting`.`eventID` = $eventID ORDER BY `meeting`.`meetingDate` DESC";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = '';
	if($result && mysqli_num_rows($result)>0)
	{
		while ($row = $result->fetch_assoc()):
			$output.="<li id=meeting-".$row['meetingID'].">";
			$output.= $row['meetingDate']." ";
			$output .= "<a class='btn btn-secondary btn-sm' role='button' href='#event-meeting-view-".$row['meetingID']."'><span class='bi bi-controller'></span> View Details</a>";
			$output.="</li>";
		endwhile;
	}
	return $output;
}

//
function getStudentMeetings($studentID)
{
	global $mysqlConn;
	$query = "SELECT DISTINCT `tournament`.`tournamentID`, `dateTournament`, `tournamentName` FROM `tournament` 
	INNER JOIN `team` ON `tournament`.`tournamentID` = `team`.`tournamentID` 
	INNER JOIN `teammateplace` ON `team`.`teamID` = `teammateplace`.`teamID` 
	WHERE `teammateplace`.`studentID` = $studentID  AND `notCompetition` = 1 ORDER BY `dateTournament` DESC";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = "";
	if($result && mysqli_num_rows($result)>0)
	{
		$output .="<h3>Meetings</h3><div>";

		$row = $result->fetch_assoc();
	    $output.=	getEventsByStudent($row['tournamentID'], $studentID);
		$output.="</div>";
	}
	return $output;
}
// use fall roster to get all events student is on, match each event to their respective meetings
function getEventsByStudent($tournamentID, $studentID)
{
	global $mysqlConn;
	$eventQuery = "SELECT `teammateplace`.`tournamenteventID`, `teamID`, `event`, `tournamentevent`.`eventID`, `place` 
	FROM `teammateplace` 
	INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` 
	INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID`
	INNER JOIN `event` ON `tournamentevent`.`eventID` = `event`.`eventID` WHERE `tournamentID` = $tournamentID 
	AND `student`.`studentID` = $studentID
	ORDER BY `event`.`event`";
	$result = $mysqlConn->query($eventQuery) or error_log("\n<br />Warning: query failed:$eventQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = "";
	if ($result && mysqli_num_rows($result)>0)
	{
		$output = "<ul>";
		while ($row = $result->fetch_assoc()):
			//show results
			$output.="<li>".$row['event'].$row['eventID'];
			$output.="<ul>";
			$output.= getEventMeetings($row['eventID']);
			$output.="</ul>";
			$output.="</li>";
		endwhile;
		$output .= "</ul>";
	}
	return $output;
}

if(!empty($_SESSION['userData'])){
	$studentID = NULL;
	$coachID = NULL;
	if($_SESSION['userData']['type'] =='student')
	{
		$studentID = getStudentID($userID);
	}
	else if ($_SESSION['userData']['type'] )
	{
		$coachID = getCoachID($userID);
	}
	$output .= '<p>You are logged in to Walton Science Olympiad Team Website!</p>';

	$output .= "<div style='display: flex; justify-content: center; vertical-align: middle;padding: 10px;'><img style='vertical-align: middle' height='256px' src='images/waltoncthulu256.png'></img></div>";
	$output .= '<div>';
	//$output .="<p style=' text-align: center'><img src='images/teamphoto.jpg' alt='team photo' width='600px'><p>";

	$output .= getCarousel();
	if(userHasPrivilege(4))
	{
		$output .= "<p><a type='button' class='btn btn-primary' href='#home-edit'><span class='bi bi-edit'></span> Edit Carousel</button></a></p>";
	}
	$output .= getInfo();
	if(userHasPrivilege(4))
	{
		$output .= "<p><a type='button' class='btn btn-primary' href='#news-edit'><span class='bi bi-edit'></span> Edit News</button></a></p>";
	}

	$output .= '<img src="'.$_SESSION['userData']['picture'].'">';
	$output .= '<p><b>Name:</b> '.$_SESSION['userData']['first_name'].' '.$_SESSION['userData']['last_name'].'</p>';
	$output .= '<p><b>Email:</b> '.$_SESSION['userData']['email'].'</p>';

	if($studentID)
	{
		$output .= "<p><a href='https://scilympiad.com/public/Student/StudentDB'>Scilympiad</a> ID: ".studentScilympiadID($studentID)."</p>";
	$output.="<p><a class='btn btn-info' role='button' href='#student-details-$studentID'><span class='bi bi-file-earmark-person'></span> Your Information</a></p>";

	}
	$output .= "</ul>";

	//Reminders
	//Show new tournaments signups with links to tournament pages, priority of events with links to events, previous tournament results.
	$tournament = "";
	if($studentID)
	{
		$tournament =	getUpcomingTournamentStudent($userID, $studentID);
	}
	else if($coachID){
		$tournament =	getUpcomingTournamentCoach();
	}
	$output .= $tournament;

	if($studentID)
	{
		//Get latest team assignments
		$myEvents = getLatestTeamTournamentStudent($studentID);
		//show student's event priority
		//$myEvents .= studentEventPriority($studentID);
		if($myEvents)
		{
			$output .= "<hr><h2>My Events</h2>" . $myEvents;
		}
		$myMeetings = getStudentMeetings($studentID);
		if($myMeetings)
		{
			$output .= "<hr><h2>My Event Meetings</h2>" . $myMeetings;
		}
		//show all previous results for this student
		$myTournamentResults = studentTournamentResults($studentID, true);
		$myAwards = studentAwards($studentID);

		if($myTournamentResults)
		{
			$output .= "<hr><h2>My Results</h2>" . $myAwards . $myTournamentResults;
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
