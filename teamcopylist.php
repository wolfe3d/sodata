<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(3);


function getTeamStudentIDs($db, $teamID)
{
	$query = "SELECT `studentID` FROM `team` INNER JOIN `teammate` ON `team`.`teamID`=`teammate`.`teamID` WHERE `team`.`teamID`= $teamID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	error_log($query);
	if($result && mysqli_num_rows($result)>0)
	{
		$studentIDs = [];
		while ($row = $result->fetch_assoc()):
			array_push($studentIDs, $row["studentID"]);
		endwhile;
		return $studentIDs;
	}
	else {
		return 0;
	}
}

$teamID = intval($_POST['myID']);
echo json_encode(getTeamStudentIDs($mysqlConn, $teamID));
?>
