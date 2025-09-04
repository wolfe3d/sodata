<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(2);

function getTeamStudents($teamID)
{
	global $mysqlConn;
	$query = "SELECT `teammate`.`studentID`, `student`.`last`, `student`.`first` FROM `team` 
		INNER JOIN `teammate` ON `team`.`teamID` = `teammate`.`teamID` 
		INNER JOIN `student` ON `teammate`.`studentID` = `student`.`studentID` 
		WHERE `team`.`teamID` = $teamID
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

$teamID = intval($_POST['team']);
echo json_encode(getTeamStudents($teamID));
?>
