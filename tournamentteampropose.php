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

function makeStudentArrayTopScore($db, $teamID)
{
	//find students and order by best score for event (not average best score)
	$rows = [];
	$query = "SELECT * FROM `teammate` INNER JOIN `teammateplace` ON `teammate`.`studentID`=`teammateplace`.`studentID` INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID`=`tournamentevent`.`tournamenteventID` INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID` WHERE `teammate`.`teamID` = $teamID ORDER BY `teammateplace`.`score` DESC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	while($row = $result->fetch_assoc()):
		array_push($rows, $row);
	endwhile;
	return $rows;
}

function makeTimeArray($db, $tournamentID)
{
	//find all available tournament times
	$rows = [];
	$query = "SELECT * FROM `tournamenttimeavailable` INNER JOIN `timeblock` ON `tournamenttimeavailable`.`timeblockID`=`timeblock`.`timeblockID` INNER JOIN `tournamentevent`  ON `tournamenttimeavailable`.`tournamenteventID`=`tournamentevent`.`tournamenteventID` INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID` WHERE `timeblock`.`tournamentID` = $tournamentID ORDER BY `timeStart`";
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
      `eventNumber` int(11),
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
				`timeblockID`,`timeStart`,`timeEnd`,`eventNumber`)
				VALUES ('$timeblockID', '$timeStart', '$timeEnd', '1')";
		//echo $query . "<br>";
		$result = $db->query($query) or print_r("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	}
	else {
		$query = "UPDATE `$tableName` SET `eventNumber` = `eventNumber`+1 WHERE `timeblockID` = $timeblockID";
		$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
//Calculate students in times and then fill in table to be read
//Think about:  Maybe put builds available throughout the day as last option
function calculateStudentsTimes($db, $teammates, $timeblocks, $studentTableName, $timeblockTableName, $resultsTableName)
{
	tempStudentInitialize($db, $studentTableName);
	tempTimeblockInitialize($db, $timeblockTableName);
	tempResultInitialize($db, $resultsTableName);
	//$results = []; //a list of all of the events with the students assigned
	foreach ($teammates as $teammate)
	{
		//get event row if it was already made
		/*$eventAdd = 0;
		foreach ($results as $row)
		{
			if($row['eventID']==$teammate['eventID'])
			{
				$eventAdd = $row;
				break;
			}
		}/*/
		//check to see if this event has already been assigned maximum students
		/*$alreadyMax = 0;
		if($eventAdd)
		{
			if($eventAdd['numberStudents']==count($eventAdd['students']))
			{
				$alreadyMax =1;
			}
		}*/
		$countAssigned = tempResultCountAssigned($db,$resultsTableName,$teammate['eventID']);
		if($countAssigned<getEventMaximumPerson($db, $teammate['eventID']))
		{
			/*if($eventAdd)
			{
				//this line could have score or place added, but this may differ depending on teammate array
				$studentAssigned = ['studentID'=>$teammate['studentID']];
				tempStudentAdd($db, $studentTableName, $teammate['studentID'],$teammate['last'], $teammate['first'], $teammate['yearGraduating']);
				array_push($eventAdd['students'], $studentAssigned);
				//the following for each does not seem like the best way to do this, but I needed a way to overwrite the result row
				foreach ($results as &$row)
				{
					if($row['eventID']==$teammate['eventID'])
					{
						$row = $eventAdd;
						break;
					}
				}
			}
			else
			{*/
				//find available timeblock and then add to output
				foreach ($timeblocks as $timeblock)
				{
					if($teammate['eventID']==$timeblock['eventID'])
					{
						//check to see if this event has already been assigned a timeBlock
						if(!$countAssigned)
						{
						//$studentAssigned = ['studentID'=>$teammate['studentID']];
						tempStudentAdd($db, $studentTableName, $teammate['studentID'],$teammate['last'], $teammate['first'],  $teammate['yearGraduating']);
						//$eventAdd = ['timeblockID'=>$timeblock['timeblockID'], 'tournamenteventID'=>$timeblock['tournamenteventID'], 'eventID'=>$timeblock['eventID'], 'event'=>$timeblock['event'],'numberStudents'=>$timeblock['numberStudents'], 'students'=>[]];
						//array_push($eventAdd['students'], $studentAssigned);
						//array_push($results, $eventAdd);
						tempResultAdd($db,$resultsTableName,$timeblock['tournamenteventID'], $timeblock['timeblockID'], $timeblock['eventID'],$teammate['studentID']);
						tempTimeblockAdd($db,$timeblockTableName,$timeblock['timeblockID'], $timeblock['timeStart'], $timeblock['timeEnd']);
						break;
						}
						else {
							//if this event has already been assigned, check that this is the timeblock that was time that was time that was chosen
							if(tempResultTimeBlock($db,$tableName,$eventID)==$timeblock['eventID'])
							{
								tempStudentAdd($db, $studentTableName, $teammate['studentID'],$teammate['last'], $teammate['first'],  $teammate['yearGraduating']);
								tempResultAdd($db,$resultsTableName,$timeblock['tournamenteventID'], $timeblock['timeblockID'], $timeblock['eventID'],$teammate['studentID']);
								tempTimeblockAdd($db,$timeblockTableName,$timeblock['timeblockID'], $timeblock['timeStart'], $timeblock['timeEnd']);
							}
						}
					}
				}
			//}
		}
	}
	return "";
}


function printTable($db, $studentTableName, $timeblockTableName, $resultsTableName)
{
	$output = "";

	//Run through times and figure out the number of different dates and print columns with colspan of times for that date
	$output .="<thead><tr><th rowspan='2' style='vertical-align:bottom;'><div>Students</div></th><th rowspan='3' style='vertical-align:bottom;'>Grade</th>";

	$dateCheck = "";
	$dateColSpan = 0;
	$dateCount = 0;
	$timeblocks = [];
	$queryTimeblock = "SELECT * FROM $timeblockTableName ORDER BY `timeStart` ASC";
	$resultTimeblock = $db->query($queryTimeblock) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($resultTimeblock->num_rows)
	{
		while ($timeblock = $resultTimeblock->fetch_assoc()):
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
	$output .="<th rowspan='3' style='vertical-align:bottom;'>Total Events</th></tr>";

//print the time for each event and date
	$output .="<tr>";
	$resultTimeblock = $db->query($queryTimeblock) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$border = "border-left:2px solid black; ";
	foreach ($timeblocks as $i=>$timeblock)
	{
			$eventNumber = $timeblock['eventNumber'];
			$output .= "<th id='timeblock-".$timeblock['timeblockID']."' colspan='$eventNumber' style='".$border." background-color:".rainbow($i)."'>" . date("g:i A",strtotime($timeblock["timeStart"])) ." - " . date("g:i A",strtotime($timeblock["timeEnd"])) . "</th>";
	}
	$output .="</tr>";

	//print the event under each time
	$output .="<tr>";
	//put sorting for last and first name in this row
	$output .="<th><a href='javascript:tournamentSort(`studentLast`)'>Last</a>, <a href='javascript:tournamentSort(`studentFirst`)'>First</a></th>";

	//Get students
	foreach ($timeblocks as $i=>$timeblock)
	{
		$timeEvents= $timeblock['eventNumber'];
		foreach ($schedule as $timeEvent)
		{
			if($timeblock['timeblockID']==$timeEvent['timeblockID'])
			{
				if($timeEvents > 1)
				{
					$border = "border-left:2px solid black; ";//isset($timeblock['border'])?$timeblock['border']:"";
					$output .= "<th id='event-".$timeEvent['tournamenteventID']."' style='".$border."background-color:".rainbow($i)."'><span>".$timeEvent['event']."</span></th>";
				}
				else
				{
					$border = "border-left:2px solid black; ";
					$output .= "<th style='$border background-color:".rainbow($i)."'></th>";
				}
				$timeEvents -=1;
			}
		}
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
				/*$timeEvents= $timeblock['events'];
				if($timeEvents)
				{
					foreach ($timeEvents as $timeEvent) {
						$checkbox = "teammateplace-".$timeEvent['tournamenteventID']."-".$rowStudent['studentID']."-".$teamID;
						$checkboxEvent = "timeblock-".$timeblock['timeblockID']." teammateEvent-".$timeEvent['tournamenteventID']." teammateStudent-".$rowStudent['studentID'];

						$query = "SELECT * FROM `teammateplace` WHERE `tournamenteventID` =  ".$timeEvent['tournamenteventID']." AND `studentID` = ".$rowStudent['studentID']." AND `teamID` = $teamID";
						$resultTeammateplace = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
						$border = isset($timeblock['border'])?$timeblock['border']:"";
						$output .="<td style='$border background-color:".rainbow($i)."' class='$checkboxEvent' data-timeblock='".$timeblock['timeblockID']."'>";
						$checked = mysqli_num_rows($resultTeammateplace)?" checked ":"";
						$timeEvent['eventTotal'] +=$checked?1:0;
						$output .=$checked?"<div class='fa'>&#xf00c;</div>":"";
						$output .="</td>";
					}
				}
				else {
					$border = isset($timeblock['border'])?$timeblock['border']:"";
					$output .= "<td style='$border background-color:".rainbow($i)."'></th>";
				}*/
			}
			$output .="<td id='studenttotal-".$rowStudent['studentID']."'></td></tr>";
	endwhile;
	}
	else {
		exit("Make sure to add students to this team before this step!");
	}
	//print the total signed up for each event
	$errorSeniors = $totalSeniors > 7 ? "<span class='error'>Too many</span>":"";
	$output .="</tbody><tfoot><tr><td><strong>$totalStudents</strong> Total Teammates</td><td><strong>$totalSeniors</strong> Seniors $errorSeniors</td>";
	/*foreach ($timeblocks as $i=>$timeblock) {
		$timeEvents= $timeblock['events'];
		if($timeEvents)
		{
			foreach ($timeEvents as $timeEvent) {
				$output .= "<td data-eventmax='".$timeEvent['numberStudents']."' id='eventtotal-".$timeEvent['tournamenteventID']."' style='$border background-color:".rainbow($i)."'>".$timeEvent['eventTotal']." </td>";
			}
		}
		else {
			$border = isset($timeblock['border'])?$timeblock['border']:"";
			$output .= "<td style='$border background-color:".rainbow($i)."'></td>";
		}
	}*/
	$output .="</tr>";

	$output .="</tfoot></table></form>";
	echo $output;
}
//TODO: Check to see if another team for this tournament has been assigned.
//Get team and tournament row information
$query = "SELECT * FROM `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID` WHERE `teamID` = $teamID";
$resultTeam = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$rowTeam = $resultTeam->fetch_assoc();

//Remove commented lines below if unneeded
//Get tournament times
/*
$query = "SELECT * FROM `timeblock` WHERE `tournamentID` = ".$rowTeam['tournamentID']." ORDER BY `timeStart`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
*/
echo "tournamentID:" . $rowTeam['tournamentID'] ."<br><br>StudentArray<br>";
$studentsTop = makeStudentArrayTopScore($mysqlConn, $teamID);
//print_r ($studentsTop);
echo "<br><br>Timeblocks<br>";
$timeblocks = makeTimeArray($mysqlConn, $rowTeam['tournamentID']);
//print_r ($timeblocks);
//echo "<br><br>Events Table<br>";
$events = getEventsTable($mysqlConn);
print_r ($events);
echo "<br><br>Calculated Student Times<br>";

calculateStudentsTimes($mysqlConn,$studentsTop, $timeblocks, 'temp_studentsMaxScore', 'temp_timeblocks', 'temp_results');
//print_r ($possible);
echo "<br><br>Students Assigned<br>";
//print_r ($students);

echo "<span id='myTitle'>".$rowTeam['tournamentName'].": ".$rowTeam['teamName']."</span></h2><div id='note'></div>";
echo "<form id='changeme' method='post' action='tournamentChangeMe.php'><table id='tournamentTable' class='tournament'>";

printTable($mysqlConn, 'temp_studentsMaxScore', 'temp_timeblocks', 'temp_results');
?>
<br>
<form id="addTo" method="post" action="tournamenteventadd.php">
	<p>
		<input class="button" type="button" onclick="window.history.back()" value="Return" />
	</p>
</form>
