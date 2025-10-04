<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(2);
//TODO: Fix me

function getEventLeaders($schoolID, $year)
{
	global $mysqlConn;
	$query = "SELECT DISTINCT `first`, `last`, `student`.`studentID`, `event`.`event`
	FROM `eventleader` 
	INNER JOIN `student` ON `eventleader`.`studentID`= `student`.`studentID` 
	INNER JOIN `event` ON `eventleader`.`eventID`=`event`.`eventID`
	WHERE `student`.`active` AND `student`.`schoolID` = $schoolID AND `year`=$year
	ORDER BY `student`.`last`, `student`.`first`";

	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0)
	{
		$students = [];
		while ($row = $result->fetch_assoc()):
			$student = ["studentID"=>$row["studentID"],"last"=>$row["last"],"first"=>$row["first"], "event"=>$row["event"]];
			array_push($students, $student);
		endwhile;
		return $students;
	}
	else {
		return 0;
	}
}
$year = getCurrentSOYear();
echo json_encode(getEventLeaders($schoolID, $year));
?>