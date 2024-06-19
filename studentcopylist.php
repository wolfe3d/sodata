<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(2);


function getStudents($schoolID)
{
	global $mysqlConn;
	$query = "SELECT * FROM `student` 
	WHERE `student`.`active` = 1 AND `schoolID` = $schoolID ORDER BY `last`,`first` ASC";
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

echo json_encode(getStudents($_SESSION['userData']['schoolID']));
?>