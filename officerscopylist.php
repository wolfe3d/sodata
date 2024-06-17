<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(2);
//TODO: Fix me

function getStudents($schoolID)
{
	global $mysqlConn;
	$query = "SELECT DISTINCT `student`.`studentID`, `student`.`last`, `student`.`first`, `student`.`email`, `student`.`emailSchool`, `officer`.`position`
	FROM `officer` 
	INNER JOIN `student` USING (`studentID`) 
	WHERE `student`.`active` = 1 AND `officer`.`year` = $year
    AND `student`.`schoolID` = $_SESSION['userData']['schoolID'];
	ORDER BY `officer`.`officerID` ASC";
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