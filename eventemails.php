<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(1);
$schoolID = $_SESSION['userData']['schoolID'];
$eventID = intval($_POST['myID']);
$year = getCurrentSOYear();
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['userID']);
$studentIDWhere = "";
if($studentID)
{
	$studentIDWhere ="AND `student`.`studentID` != $studentID";
}

//Use Tournament ID to Find Student List
function getEventStudentListTournament($db, $schoolID, $year, $tournamentID, $eventID)
{
	$query = "SELECT `student`.`studentID`, `student`.`last`, `student`.`first`, `student`.`email`, `student`.`emailSchool`, `event`.`event` FROM `tournamentevent`
	INNER JOIN `event` ON `tournamentevent`.`eventID` = `event`.`eventID`
	INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID`
	INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID`
	WHERE `tournamentevent`.`tournamentID`=$tournamentID AND `tournamentevent`.`eventID`=$eventID
	ORDER BY `student`.`last`, `student`.`first`";
	return printEmailTable($db, $query, $eventID, $year, $schoolID);
}

//Print All students that have competed in an event by this year, exclude graduated students
function getEventStudentListAllCompetitors($db, $schoolID, $eventID)
{
	$year = getCurrentSOYear();
	$query = "SELECT DISTINCT `student`.`studentID`, `student`.`last`, `student`.`first`, `student`.`email`, `student`.`emailSchool`, `event`.`event` FROM `tournament` 
	INNER JOIN `tournamentevent` USING (`tournamentID`) 
	INNER JOIN `event` USING (`eventID`) 
	INNER JOIN `teammateplace` USING (`tournamenteventID`) 
	INNER JOIN `student` USING (`studentID`) 
	WHERE `student`.`schoolID`=$schoolID AND `student`.`active` = 1 AND `tournamentevent`.`eventID`=$eventID AND `tournament`.`notCompetition`=0 AND `student`.`yearGraduating` <= $year";
	return printEmailTable($db, $query, $eventID, $year, $schoolID);
}


$eventName = getEventName($mysqlConn,$eventID);
$output="<h2>$eventName</h2>";

//Find Roster tournaments (not competitions)
$tournamentIDs=getYearTeamTournaments($mysqlConn, $schoolID, $year);
if($tournamentIDs)
{
    //print each noncompetition tournament event members
	for ($i=0; $i<count($tournamentIDs); $i++)
	{
		$output .= "<h3>". $tournamentIDs[$i]["name"] ."</h3>";
		$output .= getEventStudentListTournament($mysqlConn,$schoolID, $year, $tournamentIDs[$i]["id"],$eventID);
	}
}

//Print all students who have competed even those not assigned to the event
$allcompetitors = getEventStudentListAllCompetitors($mysqlConn, $schoolID, $eventID);
if($allcompetitors)
{
    $output .= "<h3>All Active Students that have Competed in this Event</h3>";
    $output .= $allcompetitors;
}

$output.= "<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>";
echo $output;
?>
