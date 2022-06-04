<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(2);

//check to see if the student is signed up for this event on the team or if they filled in
function onTeamEvent($db, $tournamentID, $studentID, $eventID)
{
	$query = "SELECT `student`.`studentID` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE `eventID` = $eventID and `tournament`.`tournamentID` = '$tournamentID' and `student`.`studentID` = $studentID";
	$result = $db->query($query) or print("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
//semester teams tournament hardcoded, change later
$output = "<h2>".getEventName($mysqlConn,$eventID)." Analysis</h2>";
$fallRosterDate = strval(getCurrentSOYear()-1)."-08-01";
$query = "SELECT `student`.`studentID`,`first`, `last`, `email`, `emailSchool`,`place`,`score`,`tournamentName` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE `student`.`schoolID`= " .$user->schoolID . " AND eventID = $eventID and `student`.`active` = 1 and `place` IS NOT NULL AND `tournament`.`year`=".getCurrentSOYear()." Order By `last`, `first`";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

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
				$output .= "<div>Total Score: ".$totalScore/$totalEvents."</div>";
				$output .= "<div>Average Score: ".$totalScore/$totalEvents."</div>";
				$output .= "<div>Average place: ".$totalPlace/$totalEvents."</div><br><br>";
			}
			$studentID = $row['studentID'];
			$output.= "<h3>".$row['first'] . " " . $row['last']."</h3>";
			//check to see if the student is assigned to the a team or a fill in
			if (!onTeamEvent($mysqlConn, 12, $studentID, $eventID)) //TODO change tournament ID here to this year's team
			{
				$output.= "<div class='warning'>This student has filled in on these tournaments.</div>";
			}
			$output.= "<ul>";
			$totalPlace = 0;
			$totalScore = 0;
			$totalEvents = 0;
		}
    if($row['email']){
        $output.="<li>".$row['tournamentName'] . " " . $row['place']."</li>";
				$totalPlace += $row['place'];
				$totalScore += $row['score'];
				$totalEvents += 1;
    }
endwhile;

if ($totalEvents > 0 )
{
	$output.= "</ul>";
	$output .= "<div>Total Score: ".$totalScore/$totalEvents."</div>";
	$output .= "<div>Average Score: ".$totalScore/$totalEvents."</div>";
	$output .= "<div>Average place: ".$totalPlace/$totalEvents."</div><br><br>";
}

//TODO: make a table instead
//TODO: Add the option to change the year
$output.= "<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='fa fa-arrow-circle-left'></span> Return</button></p>";
echo $output;
?>
