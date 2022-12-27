<?php
require_once  ("php/functions.php");
userCheckPrivilege(3);

$output = "";
$teamID = intval($_POST['myID']);
$thisYear = intval(getIfSet($_POST['thisYear'],0));

if(empty($teamID))
{
	echo "<div style='color:red'>teamID is not set.</div>";
	exit();
}



//find students and order by best score for event (not average best score)
function makeStudentArrayTopScore($db, $thisYear, $teamID)
{
	$whereClause = "";
	if($thisYear)
	{
		$whereClause = "AND `tournament`.`year` = ".getCurrentSOYear()." ";
	}

	$rows = [];
	//You could change MAX(`score`) to AVG(`score`) or SUM(`score`) to find the average or highest scoring
	$query = "SELECT `teammateplace`.`studentID`,`tournamentevent`.`eventID`, `event`.`numberStudents`,`last`,`first`,`yearGraduating`,`event`,MAX(`score`) as note FROM `teammateplace`
		INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID`=`tournamentevent`.`tournamenteventID`
		INNER JOIN `student` ON `teammateplace`.`studentID`=`student`.`studentID`
		INNER JOIN `event` ON `tournamentevent`.`eventID` = `event`.`eventID`
	    INNER JOIN `teammate` ON `teammate`.`studentID` = `teammateplace`.`studentID`
	    INNER JOIN `team` ON `teammateplace`.`teamID` = `team`.`teamID`
	    INNER JOIN `tournament` ON `team`.`tournamentID` = `tournament`.`tournamentID`
	    WHERE `teammate`.`teamID` = $teamID $whereClause
		AND `score` IS NOT NULL
		GROUP BY `teammate`.`studentID`,`tournamentevent`.`eventID`
		ORDER BY note DESC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	while($row = $result->fetch_assoc()):
		array_push($rows, $row);
	endwhile;
	return $rows;
}

print json_encode(makeStudentArrayTopScore($mysqlConn, $thisYear, $teamID));
