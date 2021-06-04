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

//Get team and tournament row information
$query = "SELECT * FROM `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID` WHERE `teamID` = $teamID";
$resultTeam = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$rowTeam = $resultTeam->fetch_assoc();

//Get tournament times
$query = "SELECT * FROM `timeblock` WHERE `tournamentID` = ".$rowTeam['tournamentID']." ORDER BY `timeStart`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if(mysqli_num_rows($result))
{
	$output .="<h2>Choose Students</h2><div id='note'></div>";
	$output .="<form id='changeme' method='post' action='tournamentChangeMe.php'><table>";
	$timeblocks = [];
	while ($row = $result->fetch_assoc()):
		$query = "SELECT * FROM `tournamenttimechosen` INNER JOIN `tournamentevent` ON `tournamenttimechosen`.`tournamenteventID`=`tournamentevent`.`tournamenteventID` INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID` WHERE `timeblockID` = ".$row['timeblockID'];
		$resultEvents = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$events = [];
		while ($rowEvent = $resultEvents->fetch_assoc()):
			array_push($events, $rowEvent);
		endwhile;
		$row['events'] = $events; //add count of events in timeblock
		array_push($timeblocks, $row);
	endwhile;

	//Run through times and figure out the number of different dates and print columns with colspan of times for that date
	$output .="<tr><th rowspan='3' style='vertical-align:bottom;'>Students</th>";
	$dateCheck = "";
	$dateI = 0;
	for ($i = 0; $i < count($timeblocks); $i++) {
		$eventNumber = count($timeblocks[$i]['events'])>0?count($timeblocks[$i]['events']):1;
		if($dateCheck==""){
			$dateCheck=date("F j, Y",strtotime($timeblocks[$i]["timeStart"]));
			$dateI = $eventNumber;
		}
		else {
			if($dateCheck!=date("F j, Y",strtotime($timeblocks[$i]["timeStart"]))){
				$output .= "<th colspan='$dateI' style='text-align:center;'>" . $dateCheck . "</th>";
			}
			else {
				$dateI += $eventNumber;
			}
		}
	}
	$output .= "<th colspan='$dateI' style='text-align:center;'>" . $dateCheck . "</th>";
	$output .="<th rowspan='2' style='vertical-align:bottom;'>Total Events</th></tr>";

//print the time for each event and date
	$output .="<tr>";
	for ($i = 0; $i < count($timeblocks); $i++) {
		$eventNumber = count($timeblocks[$i]['events'])>0?count($timeblocks[$i]['events']):1;
		$output .= "<th id='timeblock-".$timeblocks[$i]['timeblockID']."' colspan='$eventNumber' style='background-color:".rainbow($i)."'>" . date("g:i A",strtotime($timeblocks[$i]["timeStart"])) ." - " . date("g:i A",strtotime($timeblocks[$i]["timeEnd"]))  . "</th>";
	}
	$output .="</tr>";

	//print the event under each time
	$output .="<tr>";
	for ($i = 0; $i < count($timeblocks); $i++) {
		$timeEvents= $timeblocks[$i]['events'];
		if($timeEvents)
		{
			for ($n = 0; $n < count($timeEvents); $n++) {
				$output .= "<th id='eventblock-".$timeEvents[$n]['eventID']."' style='background-color:".rainbow($i)."'>".$timeEvents[$n]['event']."</th>";
			}
		}
		else {
			$output .= "<th style='background-color:".rainbow($i)."'></th>";
		}

	}
	$output .="<td id='studenttotal-".$rowStudent['studentID']."'></td></tr>";

	//Get students
	$query = "SELECT * FROM `teammate` INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID` WHERE `teamID` = $teamID ORDER BY `last` ASC, `first` ASC";
	$resultStudent = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(mysqli_num_rows($resultStudent))
	{
		while ($rowStudent = $resultStudent->fetch_assoc()):
			$output .="<tr>";
			$output .="<td id='".$rowStudent['studentID']."'>".$rowStudent['last'].", " . $rowStudent['first'] ."</td>";
			for ($i = 0; $i < count($timeblocks); $i++) {
				$timeEvents= $timeblocks[$i]['events'];
				if($timeEvents)
				{
					for ($n = 0; $n < count($timeEvents); $n++) {
						$checkbox = "studentplacement-".$rowStudent['studentID']."-".$timeEvents[$n]['tournamenteventID'];
						/*$queryEventTimeChosen = "SELECT * FROM `tournamenttimechosen` WHERE `tournamenteventID` =  ".$rowEvent['tournamenteventID']." AND `timeblockID` = ".$timeblocks[$i]['timeblockID'] . " AND `teamID` = ".$rowTeam['teamID'];
						$resultEventTimeChosen = $mysqlConn->query($queryEventTimeChosen) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

						$checked = mysqli_num_rows($resultEventTimeChosen)?" checked ":"";*/
						$output .= "<td style='background-color:".rainbow($i)."'><input type='checkbox' onchange='javascript:tournamentEventTimeSet($(this))' id='$checkbox' name='$checkbox' value='' $checked></th>";
					}
				}
				else {
					$output .= "<td style='background-color:".rainbow($i)."'></th>";
				}

			}
			$output .="<td id='studenttotal-".$rowStudent['studentID']."'></td></tr>";
		endwhile;
	}
	else {
		exit("Make sure to add students to this team before this step!");
	}

	//print the total signed up for each event
	$output .="<tr><td>Total Teammates</td>";
	for ($i = 0; $i < count($timeblocks); $i++) {
		$timeEvents= $timeblocks[$i]['events'];
		if($timeEvents)
		{
			for ($n = 0; $n < count($timeEvents); $n++) {
				$output .= "<td id='eventtotal-".$timeEvents[$n]['eventID']."' style='background-color:".rainbow($i)."'></td>";
			}
		}
		else {
			$output .= "<td style='background-color:".rainbow($i)."'></td>";
		}
	}
	$output .="</tr>";

	//print the total signed up for each event
	$output .="<tr><td>Place</td>";
	for ($i = 0; $i < count($timeblocks); $i++) {
		$timeEvents= $timeblocks[$i]['events'];
		if($timeEvents)
		{
			for ($n = 0; $n < count($timeEvents); $n++) {
				$output .= "<td id='eventplacement-".$timeEvents[$n]['eventID']."' style='background-color:".rainbow($i)."'></td>";
			}
		}
		else {
			$output .= "<td style='background-color:".rainbow($i)."'></td>";
		}
	}
	$output .="</tr>";

	$output .="</table></form>";
}
else {
	exit("<div>Set available time blocks first!</div>");
}
echo $output;
?>
<br>
<form id="addTo" method="post" action="tournamenteventadd.php">
	<p>
		<input class="button" type="button" onclick="window.history.back()" value="Return" />
	</p>
</form>
