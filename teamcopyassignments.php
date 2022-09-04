<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(3);

//find all student ids and event ids from a given team
function getTeamStudentEvents($db, $teamID)
{
	$query = "SELECT `studentID`, `eventID` FROM `teammateplace` INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID`=`tournamentevent`.`tournamenteventID` WHERE `teammateplace`.`teamID`= $teamID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0)
	{
		$studentIDs = [];
		while ($row = $result->fetch_assoc()):
			array_push($studentIDs, [$row["studentID"],$row["eventID"]]);
		endwhile;
		return $studentIDs;
	}
	else {
		return 0;
	}
}

$teamID = intval($_POST['myID']);
echo json_encode(getTeamStudentEvents($mysqlConn, $teamID));
?>
