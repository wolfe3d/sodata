<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(2);
//TODO: Fix me

function getStudents($eventID)
{
	global $mysqlConn;
	$query = "SELECT DISTINCT `student`.`studentID`, `student`.`last`, `student`.`first` FROM `tournament` 
	INNER JOIN `tournamentevent` USING (`tournamentID`) 
	INNER JOIN `event` USING (`eventID`) 
	INNER JOIN `teammateplace` USING (`tournamenteventID`) 
	INNER JOIN `student` USING (`studentID`) 
	WHERE `student`.`active` = 1 AND `tournamentevent`.`eventID`= $eventID 
    AND `tournament`.`notCompetition` = 1
    AND `student`.`schoolID` = $_SESSION['userData']['schoolID'];
	ORDER BY `student`.`last`,`student`.`first`";

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
echo json_encode(getStudents($eventID));
?>