<?php
require_once  ("php/functions.php");
userCheckPrivilege(3);

$output = "";
$tournamentID = intval($_POST['myID']);

if(empty($tournamentID))
{
	echo "<div style='color:red'>tournamentID is not set.</div>";
	exit();
}


//Make Timeblock array.  Order by number of slots(events) in the timeblock.  Fewest slots is assigned first.
//TODO: Figure out what priority builds will have especially if available throughout the day as last option.  This needs to change in student priority.
function makeTimeArray($db, $tournamentID)
{
	//find all available tournament times
	$rows = [];
	$query =
"SELECT x.*, `timeblock`.`timeStart`,`timeblock`.`timeEnd`, `event`.`eventID`,`event`.`event`, `event`.`numberStudents` FROM `tournamenttimeavailable` x
  JOIN (SELECT `timeblockID`, COUNT(*) total FROM `tournamenttimeavailable` GROUP BY `timeblockID`) y
    ON y.`timeblockID` = x.`timeblockID`
INNER JOIN `timeblock` ON x.`timeblockID`=`timeblock`.`timeblockID`
INNER JOIN `tournamentevent`  ON x.`tournamenteventID`=`tournamentevent`.`tournamenteventID`
INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID`
WHERE `timeblock`.`tournamentID` = $tournamentID ORDER BY total ASC, `timeStart` ASC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	while($row = $result->fetch_assoc()):
		array_push($rows, $row);
	endwhile;
	return $rows;
}

print json_encode(makeTimeArray($mysqlConn, $tournamentID));
