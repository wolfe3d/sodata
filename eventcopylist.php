<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(2);
//TODO: Fix me

function getEventMembers($eventID, $schoolID, $year)
{
	global $mysqlConn;
    //TODO: Fix query

	function getEventStudentListTournament($tournamentID, $eventID)
{
	$query = "SELECT `student`.`studentID`, `student`.`last`, `student`.`first`, `student`.`email`, `student`.`emailSchool`, `event`.`event` FROM `tournamentevent`
	INNER JOIN `event` ON `tournamentevent`.`eventID` = `event`.`eventID`
	INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID`
	INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID`
	WHERE `tournamentevent`.`tournamentID`=$tournamentID AND `tournamentevent`.`eventID`=$eventID
	ORDER BY `student`.`last`, `student`.`first`";

	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0)
	{
		$students = [];
		while ($row = $result->fetch_assoc()):
			$student = ["studentID"=>$row["studentID"],"last"=>$row["last"],"first"=>$row["first"]];
			array_push($students, $student);
		endwhile;
		return $students;
	}
	else {
		return 0;
	}
}
$eventID = intval($_POST['myID']);
$schoolID = $_SESSION['userData']['schoolID'];
$year = getCurrentSOYear();
$tournamentID = getLatestTournamentNCSchoolID($year)['tournamentID'];
echo json_encode(getEventStudentListTournament($tounamentID, $eventID);
?>