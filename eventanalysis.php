<?php
header("Content-Type: text/plain");
//get functions required
require_once  ("php/functions.php");
//initial access check for event leaders or higher
userCheckPrivilege(2);

//get variables
$eventID = intval($_POST['myID']);
$studentID = getStudentID($_SESSION['userData']['userID']);
$year = getCurrentSOYear();
//Only allow event leaders of their event to view the analysis or any officer
if(userHasPrivilege(3) || getEventLeaderThisEvent($studentID, $year, $eventID))
{
	//user is allowed to continue
}
else
{
	exit("User does not have privilege to view this event's analysis page!");
}

function getTeamRoster()
{
	global $mysqlConn, $schoolID;
	//finds the last team used for making a roster (ie notcompetition)
	$query = "SELECT `tournamentID` FROM `tournament` WHERE `notCompetition` = 1 AND `schoolID`= $schoolID ORDER BY `tournament`.`dateTournament` DESC";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		if ($result->num_rows>0)
		{
			$row = $result->fetch_assoc();
	    	return $row['tournamentID'];
		}
	}
	return 0;
}

//check to see if the student is signed up for this event on the team or if they filled in
function onTeamEvent($tournamentID, $studentID, $eventID)
{
	global $mysqlConn;
	$query = "SELECT `student`.`studentID` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE `eventID` = $eventID and `tournament`.`tournamentID` = '$tournamentID' and `student`.`studentID` = $studentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		if ($result->num_rows>0)
		{
	    return TRUE;
		}
	}
	return FALSE;
}
// Find number of meetings that a student has attended for an event
function numberOfMeetings($studentID, $eventID)
{
	global $mysqlConn;
	$query = "SELECT * FROM `meeting` INNER JOIN `meetingattendance` ON `meeting`.`eventID` = $eventID AND `meetingattendance`.`meetingID` = `meeting`.`meetingID`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$count = 0;
	while($row = $result -> fetch_assoc()):
		// value of 1 for attendance indicates student was present
		if($row['studentID'] == $studentID AND $row['attendance'] == 1)
		{
			$count += 1;
		}
	endwhile;
	return $count;
}

$output = "<h2>".getEventName($eventID)." Analysis</h2>";
$output .="<div><a class='btn btn-primary' role='button' href='#event-emails-$eventID' data-toggle='tooltip' data-placement='top' title='Get emails'><span class='bi bi-envelope'> Get Emails</span></a><div>";

$query = "SELECT * FROM `meeting` WHERE `meeting`.`eventID` = $eventID ORDER BY `meeting`.`meetingDate` DESC";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$output .= "<hr><h2 id='meetings'>Meetings</h2>";
//$output .= "<table class='table table-striped table-hover table-condensed'>";
$output .= "<div id='meetingList'><br>";
while($row = $result -> fetch_assoc()):
	if($row['meetingTypeID'] == 1)
	{
		// check if eventID matches meeting's eventID
		if($row['eventID'] == $eventID)
		{
			$output .= "<h3>".$row['meetingDate']."</h3>";
			$timeDifference = time() - strtotime($row['meetingDate'].$row['meetingTimeOut']);
			if(userHasPrivilege(2) && ($timeDifference <= 86400))
			{
				$meetingID = $row['meetingID'];
				$output .= "<a class='btn btn-warning btn-sm' role='button' href='#attendance-edit-$meetingID'><span class='bi bi-pencil-square'></span> Edit Meeting</a>";
				//$output .= "<a class='btn btn-warning btn-sm' role='button' href='#attendance-edit-".$row['meetingID']."'><span class='bi bi-pencil-square'></span> Edit Meeting</a>";
			}
			if(userHasPrivilege(5))
			{
				$meetingID = $row['meetingID'];
				$output .= "<a class='btn btn-warning btn-sm' role='button' href='#attendance-edit-$meetingID'><span class='bi bi-pencil-square'></span> Edit Meeting</a>";
			}
			//$output .= "<ul><li>ID: ".$row['meetingID']."</li>";
			$output .= "<li>Time In: ".date('h:i A', strtotime($row['meetingTimeIn']))."</li>"; // make time output consistent with attendance time input
			$output .= "<li>Time Out: ".date('h:i A', strtotime($row['meetingTimeOut']))."</li>";
			$output .= "<li>Description: ".$row['meetingDescription']."</li>";
			$output .= "<li>Homework: ".$row['meetingHW']."</li></ul>";
		}
	}
	
endwhile;
$output .= "</div>";

$output .= "<hr>";
//TODO: ORDER BY Average Score, highest ->lowest
//Maybe: Put in table, note if student is listed in a team (notCompetition)
$query = "SELECT `student`.`studentID`,`first`, `last`, `email`, `emailSchool`,`place`,`score`,`tournamentName`,`tournament`. `year` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE `student`.`schoolID`= $schoolID AND eventID = $eventID and `student`.`active` = 1 and `place` IS NOT NULL AND `tournament`.`notCompetition`=0 AND `tournament`.`year`<=".getCurrentSOYear()." Order By `last` ASC, `first` ASC, `tournament`.`dateTournament` DESC";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$teamRoster = getTeamRoster();
$studentID = 0;
$totalPlace = 0;
$totalScore = 0;
$totalTournaments = 0;
$students = [];
while ($row = $result->fetch_assoc()):
		if ($studentID !=$row['studentID'])
		{
			//print out last student
			if($studentID)
			{
				$output.= "</ul>";
			}
			if ($totalTournaments > 0 )
			{
				$output .= "<div>Total Score: ".$totalScore."</div>";
				$output .= "<div>Average Score: ".$totalScore/$totalTournaments."</div>";
				$output .= "<div>Average place: ".$totalPlace/$totalTournaments."</div>";
				$output .= "<div>Meetings attended: ".numberOfMeetings($studentID, $eventID)."</div><br><br>";

				$students[$studentID]['scoreTotal']=$totalScore;
				$students[$studentID]['scoreAvg']=$totalScore/$totalTournaments;
				$students[$studentID]['placeAvg']=$totalPlace/$totalTournaments;
				$students[$studentID]['tournamentTotal']=$totalTournaments;
			}
			//start next student
			$studentID = $row['studentID'];
			$students[$studentID]['studentID'] = $row['studentID'];
			$students[$studentID]['last'] = $row['last'];
			$students[$studentID]['first'] = $row['first'];

			$output.= "<h3>".$row['first'] . " " . $row['last']."</h3>";
			//check to see if the student is assigned to the a team or a fill in
			if ($teamRoster && !onTeamEvent($teamRoster, $studentID, $eventID)) 
			{
				$output.= "<div class='alert alert-warning'>This student is not currently assigned to this event in the Team Roster.</div>";
			}
			$output.= "<ul>";
			$totalPlace = 0;
			$totalScore = 0;
			$totalTournaments = 0;
		}
    if($row['email']){
        $output.="<li>".$row['tournamentName'] . " (" . $row['year'] .") " . $row['place']."</li>";
				$totalPlace += $row['place'];
				$totalScore += $row['score'];
				$totalTournaments += 1;
    }
endwhile;

//finish last student
if ($totalTournaments > 0 )
{
	$output.= "</ul>";
	$output .= "<div>Total Score: ".$totalScore."</div>";
	$output .= "<div>Average Score: ".$totalScore/$totalTournaments."</div>";
	$output .= "<div>Average place: ".$totalPlace/$totalTournaments."</div>";
	$output .= "<div>Meetings attended: ".numberOfMeetings($studentID, $eventID)."</div><br><br>";

	$students[$studentID]['tournamentTotal']=$totalTournaments;
	$students[$studentID]['scoreTotal']=$totalScore;
	$students[$studentID]['scoreAvg']=$totalScore/$totalTournaments;
	$students[$studentID]['placeAvg']=$totalPlace/$totalTournaments;
}

// get the total column to sort it by that
$totals = array_column($students, 'scoreTotal');
$tournamentNumber = array_column($students, 'tournamentTotal');
$placeAvg = array_column($students, 'placeAvg');
array_multisort($totals,SORT_DESC,$placeAvg, SORT_ASC, $students);
$output .="<h2>Summary</h2>";
$output .="<table class='table table-striped table-hover'><thead class='table-dark'><tr><th scope='col'>Name</th><th scope='col'>Tournaments</th><th scope='col'>Total Score</th><th scope='col'>Average Score</th><th scope='col'>Average Place</th></tr></thead>";
$output .="<tbody>";
$studentNotOnEvent = 0;
foreach ($students as &$student) {
	$onEvent = "";
	if (!onTeamEvent($teamRoster, $student['studentID'], $eventID))
	{
		$studentNotOnEvent = 1;
		$onEvent ="*";
	}
	
    $output .="<tr><th scope='row'>".$student['last'].", ".$student['first']."$onEvent</th><td>".$student['tournamentTotal']."</td><td>".$student['scoreTotal']."</td><td>".$student['scoreAvg']."</td><td>".$student['placeAvg']."</td></tr>";
}
$output .="</tbody></table>";
if ($studentNotOnEvent)
{
	$output.= "<div class='alert alert-warning'>*This student is not currently assigned to this event in the Team Roster.  They filled in or were previously assigned.</div>";
}
//TODO: Add the option to change the year
$output.= "<br><p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>";
echo $output;
?>
