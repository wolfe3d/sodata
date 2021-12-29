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
	$query = "SELECT `teammate`.`studentID`,`eventID`, `last`,`first`,`yearGraduating`,`score` FROM `teammate`
	INNER JOIN `teammateplace` ON `teammate`.`studentID`=`teammateplace`.`studentID`
	INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID`=`tournamentevent`.`tournamenteventID`
	INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID`
	WHERE `teammate`.`teamID` = $teamID
	ORDER BY `teammateplace`.`score` DESC";
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
	$query = "SELECT DISTINCT x.`studentID`,`eventID`, `last`,`first`,`yearGraduating`,myaverage FROM `teammateplace` x
JOIN (SELECT `studentID`, `eventID`, AVG(`place`) myaverage FROM `teammateplace`
INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID`=`tournamentevent`.`tournamenteventID` GROUP BY `studentID`,`eventID`) y
	ON x.`studentID`=y.`studentID`
INNER JOIN `teammate` ON x.`studentID`=`teammate`.`studentID`
INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID`
WHERE
`teammate`.`teamID` = $teamID
AND
  myaverage IS NOT NULL
  AND
  `score` IS NOT NULL
ORDER BY myaverage  ASC";
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
	$query = "SELECT DISTINCT x.`studentID`,`eventID`, `last`,`first`,`yearGraduating`,myaverage FROM `teammateplace` x
	JOIN (SELECT `studentID`, `eventID`, AVG(`score`) myaverage FROM `teammateplace`
	INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID`=`tournamentevent`.`tournamenteventID` GROUP BY `studentID`,`eventID`) y
	ON x.`studentID`=y.`studentID`
	INNER JOIN `teammate` ON x.`studentID`=`teammate`.`studentID`
	INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID`
	WHERE
	`teammate`.`teamID` = $teamID
	AND
	myaverage IS NOT NULL
	AND
	`score` IS NOT NULL
	ORDER BY myaverage  ASC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	while($row = $result->fetch_assoc()):
		array_push($rows, $row);
	endwhile;
	return $rows;
}

//Make Timeblock array.  Order by number of slots(events) in the timeblock.  Fewest slots is assigned first.
//TODO: Figure out what priority builds will have especially if available throughout the day as last option
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
      PRIMARY KEY(`tempeventID`)
    )";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

}
function tempResultAdd($db,$tableName,$tournamenteventID, $timeblockID, $eventID, $studentID)
{
	$query = "INSERT INTO `$tableName` (
				`tournamenteventID`,`timeblockID`,`eventID`,`studentID`)
				VALUES ('$tournamenteventID', '$timeblockID', '$eventID', '$studentID')";
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
function tempResultStudentTotal($db,$tableName)
{
	$query = "SELECT DISTINCT `studentID` FROM `$tableName`";
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	return $result->num_rows;
}
//Count the number of seniors already assigned
function tempResultSeniorTotal($db,$tableName)
{
	$query = "SELECT DISTINCT `student`.`studentID` FROM `$tableName` INNER JOIN `student` ON `$tableName`.`studentID`=`student`.`studentID` WHERE `yearGraduating`<=".getCurrentSOYear();
	$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	return $result->num_rows;
}
//Calculate students in times and then fill in table to be read
function calculateStudentsTimes($db, $teammates, $timeblocks, $studentTableName, $timeblockTableName, $resultsTableName)
{
	tempStudentInitialize($db, $studentTableName);
	tempTimeblockInitialize($db, $timeblockTableName);
	tempResultInitialize($db, $resultsTableName);
	foreach ($teammates as $teammate)
	{
		//Check to see that there is no more than 15 students assigned and 7 seniors OR that this student has already been assigned
		if((tempResultStudentTotal($db,$resultsTableName) <= 15 && tempResultSeniorTotal($db,$resultsTableName) <= 7) || tempResultStudentTotal($db,$resultsTableName,$teammate['studentID']))
		{
		$countAssigned = tempResultCountAssigned($db,$resultsTableName,$teammate['eventID']);
		//check to see if this person has already been assigned to this event
		if(!tempResultStudentAssignedToEvent($db,$resultsTableName,$teammate['eventID'],$teammate['studentID']))
		{
			if($countAssigned<getEventMaximumPerson($db, $teammate['eventID']))
			{
				//find available timeblock and then add to output
				foreach ($timeblocks as $timeblock)
				{
					if($teammate['eventID']==$timeblock['eventID'])
					{
						//Check to see if student is already assigned to this timeblock
						if(!tempResultStudentAssignedToTimeblock($db,$resultsTableName,$timeblock['timeblockID'],$teammate['studentID']))
						{
							//check to see if this event has already been assigned a timeBlock, if it has been assigned is it this timeblock
							if(!$countAssigned || tempResultTimeBlock($db,$resultsTableName,$timeblock['eventID'])==$timeblock['timeblockID'])
							{
								tempStudentAdd($db, $studentTableName, $teammate['studentID'],$teammate['last'], $teammate['first'],  $teammate['yearGraduating']);
								tempResultAdd($db,$resultsTableName,$timeblock['tournamenteventID'], $timeblock['timeblockID'], $timeblock['eventID'],$teammate['studentID']);
								tempTimeblockAdd($db,$timeblockTableName,$timeblock['timeblockID'], $timeblock['timeStart'], $timeblock['timeEnd']);
								break;
							}
						}
					}
				}
			}
		}
	}
}
	return "";
}


function printTable($db, $studentTableName, $timeblockTableName, $resultsTableName)
{
	$output = "";

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
				$dateColSpan = 1;
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
	$output .="<th rowspan='3' style='$border vertical-align:bottom;'>Total Events</th></tr>";

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
	foreach ($timeblocks as $i=>$timeblock)
	{
		$timeEvents= $timeblock['eventNumber'];
		$queryEvents = "SELECT DISTINCT `event`.`eventID`, `event`, `tournamenteventID` FROM `$resultsTableName` INNER JOIN `event` ON `$resultsTableName`.`eventID`=`event`.`eventID` WHERE `timeblockID`= ".$timeblock['timeblockID']." ORDER BY `event` ASC";
		$resultEvents = $db->query($queryEvents) or error_log("\n<br />Warning: query failed:$queryEvents. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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

	$output .="<td id='studenttotal-empty'></td></tr></thead><tbody>";

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
						$output .="<div class='fa'>&#xf00c;</div>";
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
	echo $output;
}
//TODO: Check to see if another team for this tournament has been assigned.
//Get team and tournament row information
$query = "SELECT * FROM `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID` WHERE `teamID` = $teamID";
$resultTeam = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$rowTeam = $resultTeam->fetch_assoc();


$timeblocks = makeTimeArray($mysqlConn, $rowTeam['tournamentID']);
$events = getEventsTable($mysqlConn);

echo "<span id='myTitle'>".$rowTeam['tournamentName'].": ".$rowTeam['teamName']."</span></h2><div id='note'></div>";
echo "<form id='changeme' method='post' action='tournamentChangeMe.php'>";

echo "<p>teamID=$teamID</p>";

echo "<h2>Students Assigned by Average Placement:TODO</h2>";
$studentsAvgPlace = makeStudentArrayAvgPlace($mysqlConn, $teamID);
//print_r ($studentsTop);
calculateStudentsTimes($mysqlConn,$studentsAvgPlace, $timeblocks, 'temp_studentsAvgPlace', 'temp_timeblocks1', 'temp_results1');
printTable($mysqlConn, 'temp_studentsAvgPlace', 'temp_timeblocks1', 'temp_results1');

echo "<h2>Students Assigned by Average Score:TODO</h2>";
//TODO:
$studentsAvgScore = makeStudentArrayAvgScore($mysqlConn, $teamID);
//print_r ($studentsTop);
calculateStudentsTimes($mysqlConn,$studentsAvgScore, $timeblocks, 'temp_studentsAvgScore', 'temp_timeblocks2', 'temp_results2');
printTable($mysqlConn, 'temp_studentsAvgScore', 'temp_timeblocks2', 'temp_results2');

echo "<h2>Students Assigned by Maximum Score (Done)</h2>";
$studentsTop = makeStudentArrayTopScore($mysqlConn, $teamID);
//print_r ($studentsTop);
calculateStudentsTimes($mysqlConn,$studentsTop, $timeblocks, 'temp_studentsMaxScore', 'temp_timeblocks', 'temp_results');
printTable($mysqlConn, 'temp_studentsMaxScore', 'temp_timeblocks', 'temp_results');


?>
<br>
<form id="addTo" method="post" action="tournamenteventadd.php">
	<p>
		<input class="button" type="button" onclick="window.history.back()" value="Return" />
	</p>
</form>
