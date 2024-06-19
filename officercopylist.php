<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(2);

function getOfficers($schoolID, $year)
{
	global $mysqlConn;
	$query = "SELECT DISTINCT `student`.`first`, `student`.`last`, `student`.`studentID`
	FROM `officer` 
	INNER JOIN `student` USING (`studentID`) 
	WHERE `student`.`active` AND `student`.`schoolID` = $schoolID AND `year`= $year
	ORDER BY `officer`.`officerID`";
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

$schoolID = $_SESSION['userData']['schoolID'];
$year = getCurrentSOYear();
echo json_encode(getOfficers($schoolID, $year));
?>