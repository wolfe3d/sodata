<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(2);
$schoolID = $_SESSION['userData']['schoolID'];

function getTeamRoster($db, $schoolID)
{
	//finds the last team used for making a roster (ie notcompetition)
	$query = "SELECT `tournamentID` FROM `tournament` WHERE `notCompetition` = 1 AND `schoolID`= $schoolID ORDER BY `tournament`.`dateTournament` DESC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
function onTeamEvent($db, $tournamentID, $studentID, $eventID)
{
	$query = "SELECT `student`.`studentID` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE `eventID` = $eventID and `tournament`.`tournamentID` = '$tournamentID' and `student`.`studentID` = $studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		if ($result->num_rows>0)
		{
	    return TRUE;
		}
	}
	return FALSE;
}

$eventID = intval($_POST['myID']);
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['userID']);
$output = "<h2>".getEventName($mysqlConn,$eventID)." Analysis</h2>";

$output .="<div><a class='btn btn-primary' role='button' href='#event-emails-$eventID' data-toggle='tooltip' data-placement='top' title='Get emails'><span class='bi bi-envelope'> Get Emails</span></a><div>";
//TODO: ORDER BY Average Score, highest ->lowest
//Maybe: Put in table, note if student is listed in a team (notCompetition)
$query = "SELECT `student`.`studentID`,`first`, `last`, `email`, `emailSchool`,`place`,`score`,`tournamentName`,`tournament`. `year` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE `student`.`schoolID`= $schoolID AND eventID = $eventID and `student`.`active` = 1 and `place` IS NOT NULL AND `tournament`.`year`<=".getCurrentSOYear()." Order By `last`, `first`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$teamRoster = getTeamRoster($mysqlConn, $schoolID);
$studentID = 0;
$totalPlace = 0;
$totalScore = 0;
$totalEvents = 0;
while ($row = $result->fetch_assoc()):
		if ($studentID !=$row['studentID'])
		{
			if($studentID)
			{
				$output.= "</ul>";
			}
			if ($totalEvents > 0 )
			{
				$output .= "<div>Total Score: ".$totalScore."</div>";
				$output .= "<div>Average Score: ".$totalScore/$totalEvents."</div>";
				$output .= "<div>Average place: ".$totalPlace/$totalEvents."</div><br><br>";
			}
			$studentID = $row['studentID'];
			$output.= "<h3>".$row['first'] . " " . $row['last']."</h3>";
			//check to see if the student is assigned to the a team or a fill in
			if ($teamRoster && !onTeamEvent($mysqlConn, $teamRoster, $studentID, $eventID)) //TODO change tournament ID here to this year's team
			{
				$output.= "<div class='warning'>This student is not currently assinged to this event in the Team Roster.</div>";
			}
			$output.= "<ul>";
			$totalPlace = 0;
			$totalScore = 0;
			$totalEvents = 0;
		}
    if($row['email']){
        $output.="<li>".$row['tournamentName'] . " (" . $row['year'] .") " . $row['place']."</li>";
				$totalPlace += $row['place'];
				$totalScore += $row['score'];
				$totalEvents += 1;
    }
endwhile;

if ($totalEvents > 0 )
{
	$output.= "</ul>";
	$output .= "<div>Total Score: ".$totalScore."</div>";
	$output .= "<div>Average Score: ".$totalScore/$totalEvents."</div>";
	$output .= "<div>Average place: ".$totalPlace/$totalEvents."</div><br><br>";
}

//TODO: make a table instead
//TODO: Add the option to change the year
$output.= "<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>";
echo $output;
?>
