<?php
require_once  ("php/functions.php");
userCheckPrivilege(3);

$output = "";
$teamID = intval($_POST['myID']);
if(empty($teamID))
{
	echo "<div style='color:red'>teamID is not set.</div>";
	exit();
}



//find students and order by best score for event (not average best score)
function makeStudentArrayTopScore($db, $teamID)
{
	$rows = [];
	$query = "SELECT `teammate`.`studentID`,`tournamentevent`.`eventID`, `event`.`numberStudents`,`last`,`first`,`yearGraduating`,`event`,`score` as note FROM `teammate`
	INNER JOIN `teammateplace` ON `teammate`.`studentID`=`teammateplace`.`studentID`
	INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID`=`tournamentevent`.`tournamenteventID`
	INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID`
	INNER JOIN `event` ON `tournamentevent`.`eventID` = `event`.`eventID`
	WHERE `teammate`.`teamID` = $teamID
	AND `score` IS NOT NULL
	ORDER BY note DESC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	while($row = $result->fetch_assoc()):
		array_push($rows, $row);
	endwhile;
	return $rows;
}

print json_encode(makeStudentArrayTopScore($mysqlConn, $teamID));
