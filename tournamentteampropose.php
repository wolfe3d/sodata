<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
require_once  ("functions.php");

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
	$query = "SELECT `teammate`.`studentID`,`eventID`, `last`,`first`,`yearGraduating`,`score` as note FROM `teammate`
	INNER JOIN `teammateplace` ON `teammate`.`studentID`=`teammateplace`.`studentID`
	INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID`=`tournamentevent`.`tournamenteventID`
	INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID`
	WHERE `teammate`.`teamID` = $teamID
	AND `score` IS NOT NULL
	ORDER BY note DESC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	while($row = $result->fetch_assoc()):
		array_push($rows, $row);
	endwhile;
	return $rows;
}

//find students and order by average placement
function makeStudentArrayAvgPlace($db, $teamID)
{
	$rows = [];
	$query = "SELECT DISTINCT x.`studentID`,`eventID`, `last`,`first`,`yearGraduating`, `score`,  note FROM `teammateplace` x
JOIN (SELECT `studentID`, `eventID`, AVG(`place`) as note FROM `teammateplace`
INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID`=`tournamentevent`.`tournamenteventID` GROUP BY `studentID`,`eventID`) y
	ON x.`studentID`=y.`studentID`
INNER JOIN `teammate` ON x.`studentID`=`teammate`.`studentID`
INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID`
WHERE
`teammate`.`teamID` = $teamID
AND
  note IS NOT NULL
  AND
  `score` IS NOT NULL
ORDER BY note  ASC";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	while($row = $result->fetch_assoc()):
		array_push($rows, $row);
	endwhile;
	return $rows;
}
//TODO: find students and order by average score
function makeStudentArrayAvgScore($db, $teamID)
{
	$rows = [];
	$query = "SELECT DISTINCT x.`studentID`,`eventID`, `last`,`first`,`yearGraduating`, note FROM `teammateplace` x
	JOIN (SELECT `studentID`, `eventID`, AVG(`score`) as note FROM `teammateplace`
	INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID`=`tournamentevent`.`tournamenteventID` GROUP BY `studentID`,`eventID`) y
	ON x.`studentID`=y.`studentID`
	INNER JOIN `teammate` ON x.`studentID`=`teammate`.`studentID`
	INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID`
	WHERE
	`teammate`.`teamID` = $teamID
	AND
	note IS NOT NULL
	AND
	`score` IS NOT NULL
	ORDER BY note  DESC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	while($row = $result->fetch_assoc()):
		array_push($rows, $row);
	endwhile;
	return $rows;
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

function getEventsTable($db)
{
	//get information for all events
	$rows = [];
	$query = "SELECT `eventID`,`event`,`numberStudents`, `type` FROM `event`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	while($row = $result->fetch_assoc()):
		array_push($rows, $row);
	endwhile;
	return $rows;
}

function tempStudentInitialize($db,$tableName)
{
	$query = "CREATE TEMPORARY TABLE `$tableName` (
      `studentID` int NOT NULL,
      `last` varchar(50),
      `first` varchar(50),
      `yearGraduating` int(11),
      PRIMARY KEY(`studentID`)
    )";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

}
function tempStudentAdd($db,$tableName,$studentID, $last, $first, $yearGraduating)
{
	$query = "SELECT `studentID` FROM `$tableName` WHERE `studentID`=$studentID";
	//echo $query . "<br>";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(!$result->num_rows)
	{
		$query = "INSERT INTO `$tableName` (
				`studentID`,`last`,`first`,`yearGraduating`)
				VALUES ('$studentID', '$last', '$first', '$yearGraduating')";
		//echo $query . "<br>";
		$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	}
}

function tempTimeblockInitialize($db,$tableName)
{
	$query = "CREATE TEMPORARY TABLE `$tableName` (
      `timeblockID` int NOT NULL,
      `timeStart` datetime,
      `timeEnd` datetime,
      PRIMARY KEY(`timeblockID`)
    )";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

}
function tempTimeblockAdd($db,$tableName,$timeblockID, $timeStart, $timeEnd)
{
	$query = "SELECT `timeblockID` FROM `$tableName` WHERE `timeblockID`=$timeblockID";
	//echo $query . "<br>";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(!$result->num_rows)
	{
		$query = "INSERT INTO `$tableName` (
				`timeblockID`,`timeStart`,`timeEnd`)
				VALUES ('$timeblockID', '$timeStart', '$timeEnd')";
		//echo $query . "<br>";
		$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	}
}
function tempResultInitialize($db,$tableName)
{
	$query = "CREATE TEMPORARY TABLE `$tableName` (
			`tempeventID` int NOT NULL AUTO_INCREMENT,
			`tournamenteventID` int,
      `timeblockID` int,
      `eventID` int,
      `studentID` int,
			`note` float NULL,
      PRIMARY KEY(`tempeventID`)
    )";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

}
function tempResultAdd($db,$tableName,$tournamenteventID, $timeblockID, $eventID, $studentID, $note)
{
	$query = "INSERT INTO `$tableName` (
				`tournamenteventID`,`timeblockID`,`eventID`,`studentID`,`note`)
				VALUES ('$tournamenteventID', '$timeblockID', '$eventID', '$studentID', '$note')";
		//echo $query . "<br>";
		$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
}
function tempResultModifyTimeblock($db,$tableName,$tournamenteventID, $timeblockID, $eventID, $studentID)
{
	$query = "UPDATE `$tableName` SET `timeblockID`=$timeblockID, `tournamenteventID`=$tournamenteventID
			WHERE `eventID`=$eventID AND `studentID`=$studentID";
		//echo $query . "<br>";
		$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
}
//Count number of students already assigned to an event
function tempResultCountAssigned($db,$tableName,$eventID)
{
	$query = "SELECT * FROM `$tableName` WHERE `eventID` = $eventID";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	return $result->num_rows;
}
//get the number of students that can be assigned to an event
function getEventMaximumPerson($db, $eventID)
{
	$query = "SELECT `numberStudents` FROM `event` WHERE `eventID` = $eventID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row = $result->fetch_assoc();
	return $row['numberStudents'];
}
//find timeblock that was already assigned to an event
function tempResultTimeBlock($db,$tableName,$eventID)
{
	$query = "SELECT * FROM `$tableName` WHERE `eventID` = $eventID";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row = $result->fetch_assoc();
	return $row['timeblockID'];
}
//find timeblock that was already assigned to an event
function tempResultTimeBlockIs($db,$tableName,$eventID,$timeblockID)
{
	$query = "SELECT * FROM `$tableName` WHERE `eventID` = $eventID AND `timeblockID`=$timeblockID";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	return $result->num_rows;
}
//count distinct timeblocks that are in the results
function tempResultCountTimeBlock($db,$tableName,$timeblockID)
{
	$query = "SELECT DISTINCT `eventID` FROM `$tableName` WHERE `timeblockID` = $timeblockID";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	return $result->num_rows;
}
//Count number of events that a student is assigned to
function tempResultStudentEvents($db,$tableName,$studentID)
{
	$query = "SELECT * FROM `$tableName` WHERE `studentID` = $studentID";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	return $result->num_rows;
}
//Count number of students in an event
function tempResultEventTotal($db,$tableName,$eventID)
{
	$query = "SELECT * FROM `$tableName` WHERE `eventID` = $eventID";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	return $result->num_rows;
}
//Check to see if student has already been assigned to this event
function tempResultStudentAssignedToEvent($db,$tableName,$eventID,$studentID)
{
	$query = "SELECT * FROM `$tableName` WHERE `eventID` = $eventID AND `studentID` = $studentID";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	return $result->num_rows;
}
//Check to see if student has already been assigned to this event
function tempResultStudentAssignedToTimeblock($db,$tableName,$timeblockID,$studentID)
{
	$query = "SELECT * FROM `$tableName` WHERE `timeblockID` = $timeblockID AND `studentID` = $studentID";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	return $result->num_rows;
}
//Count the number of students already assigned
function tempStudentTotal($db,$tableName)
{
	$query = "SELECT COUNT(DISTINCT(`studentID`)) as total FROM `$tableName`";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row=$result->fetch_assoc();
	return $row['total'];
}
//Count the number of seniors already assigned, this must use the student table that stores the yearGraduating
function tempSeniorTotal($db,$tableName)
{
	$query = "SELECT COUNT(DISTINCT(`studentID`)) as total FROM `$tableName` WHERE `yearGraduating`=".getCurrentSOYear();
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row=$result->fetch_assoc();
	return $row['total'];
}
//find the name of the event
function getEventName($db,$eventID)
{
	$query = "SELECT `event` FROM `event` WHERE `eventID`=$eventID";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row=$result->fetch_assoc();
	return $row['event'];
}
//Check to see if student has already been assigned to this event
function tempResultFindStudentAssignedToEvent($db,$tableName,$eventID)
{
	$query = "SELECT `$tableName`.`studentID`, `last`, `first` FROM `$tableName` INNER JOIN `student` ON `$tableName`.`studentID`=`student`.`studentID` WHERE `eventID` = $eventID";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$rows=[];
	if($result)
	{
	while($row = $result->fetch_assoc()):
		array_push($rows, $row);
	endwhile;
	}
	return $rows;
}
//reaassign student to different timeBlock
function reassignStudent($db, $eventID, $timeblocks, $studentTableName, $timeblockTableName, $resultsTableName)
{
	//check all available $timeblocks for the $eventID
	$eventTimeblocks =[];
	foreach ($timeblocks as $timeblock)
	{
		if($eventID==$timeblock['eventID'])
		{
			array_push($eventTimeblocks, $timeblock);
		}
	}
	//if there is only one $timeblock, do not change and return FALSE
	if(count($timeblock) == 1)
	{
		echo " this event has only one timeslot";
		return 0;
	}

	//find other students already assigned to this eventID
	$teammates = tempResultFindStudentAssignedToEvent($db,$resultsTableName,$eventID);
	foreach ($teammates as $n=>$teammate)
	{
	  echo " Attempting to reassign ".$teammate['first'];

		//if there is another $timeblock, then attempt to reassign the $timeblock
		$assignedTimeblock = tempResultTimeBlock($db,$resultsTableName,$timeblock['eventID']);

		//find available timeblock and then add to output
		$foundslot = 0;

		//New set of timeblocks for everyone already assinged
		$neweventTimeblocks = [];
		foreach ($eventTimeblocks as $timeblock)
		{
			if($timeblock['timeblockID']!=$assignedTimeblock)
			{
				$foundslot = 1;
				echo " Timeblock start:".$timeblock['timeStart'].".";
				//if there is more than one teammate, make sure the second one is also assigned to the same timeblock
					//Check to see if student is already assigned to this timeblock
					if(!tempResultStudentAssignedToTimeblock($db,$resultsTableName,$timeblock['timeblockID'],$teammate['studentID']))
					{
						$narrowedTimeblocks = array_push($rows, $row);
					}
					else {
						echo " Student already assigned to this timeblock (".$timeblock['timeblockID'].") Keep checking...";
					}
			}
			else
			{
				echo " old timeblock...continue checking"; //will not ever be printed because the timeblocks that are available do not include this one
			}
		}
		if(!$foundslot)
		{
				echo " <span class='warning'>No other free slots found for this student-failed</span><br>";
				return 0;
		}
		$eventTimeblocks = $narrowedTimeblocks;
	}
	$assigned =0;
	foreach ($teammates as $n=>$teammate)
	{
		foreach ($eventTimeblocks as $timeblock)
		{
			if(!$n || tempResultTimeBlockIs($db,$resultsTableName,$timeblock['eventID'],$timeblock['timeblockID']))
			{
				echo "-<span style='color:green'>added</span><br>";
				//modify these functions
				//tempStudentAdd($db, $studentTableName, $teammate['studentID'],$teammate['last'], $teammate['first'],  $teammate['yearGraduating']);
				tempResultModifyTimeblock($db,$resultsTableName,$timeblock['tournamenteventID'], $timeblock['timeblockID'], $eventID, $teammate['studentID']);
				tempTimeblockAdd($db,$timeblockTableName,$timeblock['timeblockID'], $timeblock['timeStart'], $timeblock['timeEnd']);
				$assigned = 1;
			}
		}
	}
	if($assigned)
	{
		return 1;
	}
		return 0;
}
//assign student to timeBlock
function assignStudent($db, $teammate, $timeblocks, $studentTableName, $timeblockTableName, $resultsTableName)
{
	echo " continuing to assign " . $teammate['first'];
	//find available timeblock and then add to output
	$assigned = 0;
	$foundevent = 0;
	$reassigned = 0;
	//get number of students assigned to this event
	$countAssigned = tempResultCountAssigned($db,$resultsTableName,$teammate['eventID']);
	$availableTimeblocksForStudent = []; //keep track of available timeblocks for this teammate
	$availableTimeblocksForStudentofEvent = []; //keep track of available timeblocks for this teammate
	foreach ($timeblocks as $timeblock)
	{
		if(!tempResultStudentAssignedToTimeblock($db,$resultsTableName,$timeblock['timeblockID'],$teammate['studentID']))
		{
			array_push($availableTimeblocksForStudent, $timeblock);
			if($teammate['eventID']==$timeblock['eventID'])
			{
				array_push($availableTimeblocksForStudentofEvent, $timeblock);
				$foundevent = 1;
				echo " Timeblock (".$timeblock['timeblockID'].") start:".$timeblock['timeStart'].".";
				//Check to see if student is already assigned to this timeblock
					//check to see if this event has already been assigned a timeBlock, if it has been assigned is it this timeblock
					if(!$countAssigned || tempResultTimeBlockIs($db,$resultsTableName,$timeblock['eventID'],$timeblock['timeblockID']))
					{
						echo "-<span style='color:green'>added</span><br>";
						tempStudentAdd($db, $studentTableName, $teammate['studentID'],$teammate['last'], $teammate['first'],  $teammate['yearGraduating']);
						tempResultAdd($db,$resultsTableName,$timeblock['tournamenteventID'], $timeblock['timeblockID'], $timeblock['eventID'],$teammate['studentID'],$teammate['note']);
						tempTimeblockAdd($db,$timeblockTableName,$timeblock['timeblockID'], $timeblock['timeStart'], $timeblock['timeEnd']);
						$assigned = 1;
						break;
					}
					else {
						echo " This event is assigned to another timeblock. Keep checking...";
					}
				}
			}
		else
		{
			if($teammate['eventID']==$timeblock['eventID'])
			{
				$foundevent = 1;
				echo " Student already assigned to this timeblock (".$timeblock['timeblockID'].") start:".$timeblock['timeStart'].". Keep checking...";
			}
			else {
				//Another event in this timeblock
			}
		}
	}
	if(!$foundevent)
	{
			echo " <span class='error'>Event not running in this tournament-failed</span><br>";
	}
	else if(!$assigned)
	{
		echo " <span class='warning'>Could not find available match between timeblock and student-failed</span><br>";
		//TODO: If student is not available for event in the current available timeblocks, try moving another event with multiple timeblocks...
		//Go back and reassign partner to different timeslot if there is more than one time slot for the other event
		if(!$reassigned && $availableTimeblocksForStudentofEvent) //!reassigned = only attempt one reassignment
		{
			//Reassigning should work for multiple teammates
			$reassigned = reassignStudent($db, $teammate['eventID'], $availableTimeblocksForStudentofEvent, $studentTableName, $timeblockTableName, $resultsTableName);
			if($reassigned)
			{
				assignStudent($db, $teammate, $timeblocks, $studentTableName, $timeblockTableName, $resultsTableName);
			}
		}
	}
}
//Calculate students in times and then fill in table to be read
function calculateStudentsTimes($db, $teammates, $timeblocks, $studentTableName, $timeblockTableName, $resultsTableName)
{
	echo "<input class='button fa' type='button' onclick='javascript:$(\"#$resultsTableName\").toggle();' value='&#xf0f9; Verbose' /><div id='$resultsTableName' style='display:none;'>";
	tempStudentInitialize($db, $studentTableName);
	tempTimeblockInitialize($db, $timeblockTableName);
	tempResultInitialize($db, $resultsTableName);
	foreach ($teammates as $teammate)
	{
		$studentAssigned = tempResultStudentEvents($db,$studentTableName,$teammate['studentID']);
		$totalStudents = tempStudentTotal($db,$studentTableName);
		$totalSeniors = tempSeniorTotal($db,$studentTableName);
		//Check to see that there is no more than 15 students assigned OR that this student has already been assigned
		//And check to see that there are no more than 7 seniors assigned
		$isSenior = $teammate['yearGraduating']==getCurrentSOYear()?1:0;
		echo  $teammate['note']." " .$teammate['first'].",".$totalStudents.",".$studentAssigned.",".$totalSeniors.":".$isSenior;

		if(($totalStudents < 15 && (!$isSenior || $totalSeniors < 7 )) || $studentAssigned)
		{
			echo "...attempting to add event: " . getEventName($db,$teammate['eventID']) . ".";
			//get number of students assigned to this event
			$countAssigned = tempResultCountAssigned($db,$resultsTableName,$teammate['eventID']);

			//check to see if this person has already been assigned to this event
			if(!tempResultStudentAssignedToEvent($db,$resultsTableName,$teammate['eventID'],$teammate['studentID']))
			{
				if($countAssigned<getEventMaximumPerson($db, $teammate['eventID']))
				{
					assignStudent($db, $teammate, $timeblocks, $studentTableName, $timeblockTableName, $resultsTableName);
				}
				else {
					echo " <span class='error'>Event full ($countAssigned Students)-failed</span><br>";
				}
			}
			else
			{
				echo " <span class='warning'>Student already assigned to this event!-failed</span><br>";
			}

		}
		else {
			if($totalStudents == 15)
			{
				echo " <span class='error'>15 students already assigned to team!-failed</span><br>";
			}
			else if($totalSeniors == 7)
			{
				echo " <span class='error'>7 seniors already assigned to team!-failed</span><br>";
			}
		}
//TODO: Add algorithm to add students to fill spots.
	}
	echo "</div>";
	return "";
}


function printTable($db, $studentTableName, $timeblockTableName, $resultsTableName)
{
	$output = "";
	$notescore = 0;
	//Run through times and figure out the number of different dates and print columns with colspan of times for that date
	$output .="<table id='tournamentTable$resultsTableName' class='tournament'><thead><tr><th rowspan='2' style='vertical-align:bottom;'><div>Students</div></th><th rowspan='3' style='vertical-align:bottom;'>Grade</th>";

	$dateCheck = "";
	$dateColSpan = 0;
	$dateCount = 0;
	$timeblocks = [];
	$queryTimeblock = "SELECT * FROM $timeblockTableName ORDER BY `timeStart` ASC";
	$resultTimeblock = $db->query($queryTimeblock) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($resultTimeblock->num_rows)
	{
		while ($timeblock = $resultTimeblock->fetch_assoc()):
			$timeblock['eventNumber'] = tempResultCountTimeBlock($db,$resultsTableName,$timeblock['timeblockID']);
			array_push($timeblocks, $timeblock);
		if($dateCheck==""){
			$dateCheck=date("F j, Y",strtotime($timeblock["timeStart"]));
			$dateColSpan = $timeblock['eventNumber'];
		}
		else {
			if($dateCheck!=date("F j, Y",strtotime($timeblock["timeStart"]))){
				$output .= "<th colspan='$dateColSpan' style='border-right:2px solid black;text-align:center;'>" . $dateCheck . "</th>";
				$dateCheck=date("F j, Y",strtotime($timeblock["timeStart"]));
				$dateColSpan = $timeblock['eventNumber'];
				$dateCount +=1;
				$timeblock['border'] = "border-left:2px solid black; "; //adds border at beginning of new date
			}
			else {
				$dateColSpan += $timeblock['eventNumber'];
			}
		}
	endwhile;
	}
	$output .= "<th colspan='$dateColSpan' style='text-align:center;'>" . $dateCheck . "</th>";
	$border = "border-left:2px solid black; ";
	$output .="<th rowspan='2' style='$border vertical-align:bottom;'>Total Events</th></tr>";

//print the time for each event and date
	$output .="<tr>";
	$resultTimeblock = $db->query($queryTimeblock) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$border = "border-left:2px solid black; ";
	foreach ($timeblocks as $i=>$timeblock)
	{
			$eventNumber = $timeblock['eventNumber'];
			$output .= "<th id='timeblock-".$timeblock['timeblockID']."' colspan='$eventNumber' style='".$border." background-color:".rainbow($i)."'>" . date("g:i A",strtotime($timeblock["timeStart"])) ." - " . date("g:i A",strtotime($timeblock["timeEnd"])) ."</th>";
	}
	$output .="</tr>";

	//print the event under each time
	$output .="<tr>";
	//put sorting for last and first name in this row
	$output .="<th><a href='javascript:tournamentSort(`studentLast`)'>Last</a>, <a href='javascript:tournamentSort(`studentFirst`)'>First</a></th>";

	//Get events
	$totalEvents =0;
	foreach ($timeblocks as $i=>$timeblock)
	{
		$timeEvents= $timeblock['eventNumber'];
		$queryEvents = "SELECT DISTINCT `event`.`eventID`, `event`, `tournamenteventID` FROM `$resultsTableName` INNER JOIN `event` ON `$resultsTableName`.`eventID`=`event`.`eventID` WHERE `timeblockID`= ".$timeblock['timeblockID']." ORDER BY `event` ASC";
		$resultEvents = $db->query($queryEvents) or error_log("\n<br />Warning: query failed:$queryEvents. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$totalEvents += $resultEvents->num_rows;
		while ($row = $resultEvents->fetch_assoc()):
				if($timeEvents == $timeblock['eventNumber'])
				{
					$border = "border-left:2px solid black; ";
				}
				else
				{
					$border = "";
				}
				$output .= "<th id='event-".$row['tournamenteventID']."' style='".$border."background-color:".rainbow($i)."'><span>".$row['event']."</span></th>";
				$timeEvents -=1;
		endwhile;
	}
	$border = "border-left:2px solid black; ";
	$output .="<td id='studenttotal-empty' style='$border'>$totalEvents</td></tr></thead><tbody>";

	//Get students
	$query = "SELECT * FROM $studentTableName ORDER BY `last` ASC, `first` ASC";
	$resultStudent = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($resultStudent)
	{
		$totalStudents = mysqli_num_rows($resultStudent);
		$totalSeniors = 0;
		while ($rowStudent = $resultStudent->fetch_assoc()):
			//$studentTotal = 0;  //this is done in the javascript TODO: remove this line
			$output .="<tr studentLast=".removeParenthesisText($rowStudent['last'])."  studentFirst=".removeParenthesisText($rowStudent['first']).">";

			//find student Grade
			$studentGrade = getStudentGrade($rowStudent['yearGraduating']);
			$totalSeniors += $studentGrade==12 ? 1:0;
			//output student column
			$output .="<td class='student' id='teammate-".$rowStudent['studentID']."'><a target='_blank' href='#student-details-".$rowStudent['studentID']."'>".$rowStudent['last'].", " . $rowStudent['first'] ."</a></td><td>$studentGrade</td>";
			foreach ($timeblocks as $i=>$timeblock) {
				$timeEvents = $timeblock['eventNumber'];
				$queryEvents = "SELECT DISTINCT `event`.`eventID`, `event`, `tournamenteventID` FROM `$resultsTableName` INNER JOIN `event` ON `$resultsTableName`.`eventID`=`event`.`eventID` WHERE `timeblockID`= ".$timeblock['timeblockID']." ORDER BY `event` ASC";
				$resultEvents = $db->query($queryEvents) or error_log("\n<br />Warning: query failed:$queryEvents. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
				while ($rowEvent = $resultEvents->fetch_assoc()):
					$queryResultTable = "SELECT * FROM `$resultsTableName` WHERE `eventID`= ".$rowEvent['eventID']." AND `studentID`=".$rowStudent['studentID'];
					$result= $db->query($queryResultTable) or error_log("\n<br />Warning: query failed:$queryResultTable. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
					//put a border around each color
					if($timeEvents == $timeblock['eventNumber'])
					{
						$border = "border-left:2px solid black; ";
					}
					else
					{
						$border = "";
					}
					$timeEvents -=1;

					if($result->num_rows)
					{
						$row = $result->fetch_assoc();
						$checkbox = "teammateplace-".$row['tournamenteventID']."-".$row['studentID'];
						$checkboxEvent = "timeblock-".$row['timeblockID']." teammateEvent-".$row['tournamenteventID']." teammateStudent-".$row['studentID'];
						$output .="<td style='$border background-color:".rainbow($i)."' class='$checkboxEvent' data-timeblock='".$row['timeblockID']."'>";
						$output .="<div class='fa'>&#xf00c; (".number_format($row['note'],1).")</div>";
						$notescore +=$row['note'];
						$output .="</td>";
					}
				else {
					$output .= "<td style='$border background-color:".rainbow($i)."'></td>";
				}
			endwhile;
			}
			$border = "border-left:2px solid black; ";
			$output .="<td style='$border' id='studenttotal-".$rowStudent['studentID']."'>".tempResultStudentEvents($db,$resultsTableName,$rowStudent['studentID'])."</td></tr>";
	endwhile;
	}
	else {
		exit("Make sure to add students to this team before this step!");
	}
	//print the total signed up for each event
	$errorSeniors = $totalSeniors > 7 ? "<span class='error'>Too many</span>":"";
	$output .="</tbody><tfoot><tr><td><strong>$totalStudents</strong> Total Teammates</td><td><strong>$totalSeniors</strong> Seniors $errorSeniors</td>";
	foreach ($timeblocks as $i=>$timeblock) {
		$timeEvents = $timeblock['eventNumber'];
		$queryEvents = "SELECT DISTINCT `event`.`eventID`, `event`, `tournamenteventID`,`numberStudents` FROM `$resultsTableName` INNER JOIN `event` ON `$resultsTableName`.`eventID`=`event`.`eventID` WHERE `timeblockID`= ".$timeblock['timeblockID']." ORDER BY `event` ASC";
		$resultEvents = $db->query($queryEvents) or error_log("\n<br />Warning: query failed:$queryEvents. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		while ($rowEvent = $resultEvents->fetch_assoc()):
			//put a border around each color
			if($timeEvents == $timeblock['eventNumber'])
			{
				$border = "border-left:2px solid black; ";
			}
			else
			{
				$border = "";
			}
			$timeEvents -=1;
			$countEvent = tempResultEventTotal($db,$resultsTableName,$rowEvent['eventID']);
			$class = $rowEvent['numberStudents']>$countEvent?"warning":"";
			$warningText = $rowEvent['numberStudents']>$countEvent?"***Too Few":"";
			$output .= "<td data-eventmax='".$rowEvent['numberStudents']."' class='$class' id='eventtotal-".$rowEvent['tournamenteventID']."' style='$border background-color:".rainbow($i)."'>$countEvent $warningText</td>";
		endwhile;
	}
	$output .="</tr>";

	$output .="</tfoot></table></form>";
	$output .="<div>Score = ".number_format($notescore,2)."  (This can only be compared to the same type of table (not to different tables), i.e Average Score cannot be compared to Average Place.</div><br>";
	echo $output;
}
//TODO: Check to see if another team for this tournament has been assigned.  If so, you must change timeblock calls from tournamenttimeavailable to tournamenttimechosen except for builds (makes it a bit difficult)
//Get team and tournament row information
$query = "SELECT * FROM `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID` WHERE `teamID` = $teamID";
$resultTeam = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$rowTeam = $resultTeam->fetch_assoc();


$timeblocks = makeTimeArray($mysqlConn, $rowTeam['tournamentID']);
$events = getEventsTable($mysqlConn);

echo "<h2><span id='myTitle'>".$rowTeam['tournamentName'].": ".$rowTeam['teamName']."</span></h2><div id='note'></div>";

echo "<p>teamID=$teamID; tournamentID: ".$rowTeam['tournamentID']."</p>";

echo "<h2>Students Assigned by Average Score</h2>";
$studentsAvgScore = makeStudentArrayAvgScore($mysqlConn, $teamID);
calculateStudentsTimes($mysqlConn,$studentsAvgScore, $timeblocks, 'temp_studentsAvgScore', 'temp_timeblocks2', 'temp_results2');
printTable($mysqlConn, 'temp_studentsAvgScore', 'temp_timeblocks2', 'temp_results2');

echo "<h2>Students Assigned by Average Placement</h2>";
//TODO:Check on why some students are not being assigned when there is a clear empty space
$studentsAvgPlace = makeStudentArrayAvgPlace($mysqlConn, $teamID);
//print_r ($studentsTop);
calculateStudentsTimes($mysqlConn,$studentsAvgPlace, $timeblocks, 'temp_studentsAvgPlace', 'temp_timeblocks1', 'temp_results1');
printTable($mysqlConn, 'temp_studentsAvgPlace', 'temp_timeblocks1', 'temp_results1');


echo "<h2>Students Assigned by Maximum Score</h2>";
$studentsTop = makeStudentArrayTopScore($mysqlConn, $teamID);
calculateStudentsTimes($mysqlConn,$studentsTop, $timeblocks, 'temp_studentsMaxScore', 'temp_timeblocks', 'temp_results');
printTable($mysqlConn, 'temp_studentsMaxScore', 'temp_timeblocks', 'temp_results');


?>
<br>
<form id="addTo" method="post" action="tournamenteventadd.php">
	<p>
				<input class="button fa" type="button" onclick="window.history.back()" value="&#xf0a8; Return" />
	</p>
</form>
