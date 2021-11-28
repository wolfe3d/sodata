<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
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
	$output .="<h2>";
	if(userHasPrivilege(3)){
		$output .="Adjust Teammate Assignments";
	}
	else{
		if($rowTeam['dateTournament']<date("Y-m-d")){
			//Show results as title after tournament date
			$output .="<h2>Results</h2>";
		}
		else {
			//Show schedule as title before and during tournament date
			$output .="<h2>Schedule</h2>";
		}
	}
	$output .=" <span id='myTitle'>".$rowTeam['tournamentName'].": ".$rowTeam['teamName']."</span></h2><div id='note'></div>";
	$output .="<form id='changeme' method='post' action='tournamentChangeMe.php'><table id='tournamentTable' class='tournament'>";
	$timeblocks = [];
	while ($row = $result->fetch_assoc()):
		$query = "SELECT * FROM `tournamenttimechosen` INNER JOIN `tournamentevent` ON `tournamenttimechosen`.`tournamenteventID`=`tournamentevent`.`tournamenteventID` INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID` WHERE `timeblockID` = ".$row['timeblockID']." AND `tournamenttimechosen`.`teamID`= $teamID ORDER BY `event`.`event`";
		$resultEvents = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$events = [];
		while ($rowEvent = $resultEvents->fetch_assoc()):
			$rowEvent['eventTotal']=0;
			array_push($events, $rowEvent);
		endwhile;
		$row['events'] = $events; //add count of events in timeblock
		array_push($timeblocks, $row);
	endwhile;

	//Run through times and figure out the number of different dates and print columns with colspan of times for that date
	$output .="<thead><tr><th rowspan='3' style='vertical-align:bottom;'><div>Students</div></th>";

	$dateCheck = "";
	$dateColSpan = 0;
	$dateCount = 0;
	foreach ($timeblocks as $timeblock) {
		$eventNumber = count($timeblock['events'])>0?count($timeblock['events']):1;
		if($dateCheck==""){
			$dateCheck=date("F j, Y",strtotime($timeblock["timeStart"]));
			$dateColSpan = $eventNumber;
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
				$dateColSpan += $eventNumber;
			}
		}
	}
	$output .= "<th colspan='$dateColSpan' style='text-align:center;'>" . $dateCheck . "</th>";
	$output .="<th rowspan='4' style='vertical-align:bottom;'>Total Events</th></tr>";

//print the time for each event and date
	$output .="<tr>";
	for ($i = 0; $i < count($timeblocks); $i++) {
		$eventNumber = count($timeblocks[$i]['events'])>0?count($timeblocks[$i]['events']):1;
		$border = isset($timeblocks[$i]['border'])?$timeblocks[$i]['border']:"";
		$output .= "<th id='timeblock-".$timeblocks[$i]['timeblockID']."' colspan='$eventNumber' style='".$border."background-color:".rainbow($i)."'>" . timeblockEdit($timeblocks[$i]['timeblockID'],date("g:i A",strtotime($timeblocks[$i]["timeStart"])) ." - " . date("g:i A",strtotime($timeblocks[$i]["timeEnd"])),(userHasPrivilege(3)))  . "</th>";
	}
	$output .="</tr>";

	//print the event under each time
	$output .="<tr>";
	foreach ($timeblocks as $i=>$timeblock) {
		$timeEvents= $timeblock['events'];
		if($timeEvents)
		{
			foreach ($timeEvents as $timeEvent) {
				$border = isset($timeblock['border'])?$timeblock['border']:"";
				$output .= "<th id='event-".$timeEvent['tournamenteventID']."' style='".$border."background-color:".rainbow($i)."'><span>".$timeEvent['event']."</span></th>";
			}
		}
		else {
			$border = isset($timeblock['border'])?$timeblock['border']:"";
			$output .= "<th style='$border background-color:".rainbow($i)."'></th>";
		}

	}
	$output .="<td id='studenttotal-empty'></td></tr>";

	//print the event note under each event
	$output .="<tr>";
	//put sorting for last and first name in this row
	$output .="<th><a href='javascript:tournamentSort(`studentLast`)'>Last</a>, <a href='javascript:tournamentSort(`studentFirst`)'>First</a></th>";

	foreach ($timeblocks as $i=>$timeblock) {
		$timeEvents= $timeblock['events'];
		if($timeEvents)
		{
			foreach ($timeEvents as $timeEvent) {
				$border = isset($timeblock['border'])?$timeblock['border']:"";
				$output .= "<th id='event-".$timeEvent['tournamenteventID']."' class='.rotate' style='".$border."background-color:".rainbow($i)."'>".eventNote($timeEvent['tournamenteventID'],$timeEvent['note'],(userHasPrivilege(3)))."</th>";
			}
		}
		else {
			$border = isset($timeblock['border'])?$timeblock['border']:"";
			$output .= "<th style='$border background-color:".rainbow($i)."'></th>";
		}

	}
	$output .="<td id='studenttotal-empty'></td></tr></thead><tbody>";

	//Get students
	$query = "SELECT * FROM `teammate` INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID` WHERE `teamID` = $teamID ORDER BY `last` ASC, `first` ASC";
	$resultStudent = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
 	$totalStudents = mysqli_num_rows($resultStudent);
	if($totalStudents)
	{
		while ($rowStudent = $resultStudent->fetch_assoc()):
			//$studentTotal = 0;  //this is done in the javascript TODO: remove this line
			$output .="<tr studentLast=".removeParenthesisText($rowStudent['last'])."  studentFirst=".removeParenthesisText($rowStudent['first']).">";
			/*/check to see if student is signed up for the timeblock
			/$query = "SELECT timeStart,timeEnd FROM teammateplace INNER JOIN tournamenttimechosen ON teammateplace.tournamenteventID=tournamenttimechosen.tournamenteventID INNER JOIN tournamentevent ON teammateplace.tournamenteventID=tournamentevent.tournamenteventID INNER JOIN timeblock ON timeblock.timeblockID=tournamenttimechosen.timeblockID WHERE teammateplace.studentID=".$rowStudent['studentID']." AND tournamentevent.tournamentID=".$rowTeam['tournamentID']. " GROUP BY tournamenttimechosen.timeblockID having count(*) > 1";
			$resultStudentCheck = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
			$errorStudentCheck="";
			if($resultStudentCheck){
				while ($rowStudentCheck = $resultStudentCheck->fetch_assoc()):
					$errorStudentCheck .= " <span class='modified error'>More than one event in timeBlock: ".$rowStudentCheck['timeStart']."-".$rowStudentCheck['timeEnd']."</span>";
				endwhile;
			}*/
			$errorStudentCheck =""; //TODO: Remove this line and above commented lines if javascript errorcheck function works
			//output student column
			$output .="<td class='student' id='teammate-".$rowStudent['studentID']."'><a target='_blank' href='#student-details-".$rowStudent['studentID']."'>".$rowStudent['last'].", " . $rowStudent['first'] ."</a>$errorStudentCheck</td>";
			foreach ($timeblocks as $i=>$timeblock) {
				$timeEvents= $timeblock['events'];
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
						//$studentTotal +=$checked?1:0;  //done in javascript
						if(userHasPrivilege(3)){
							$output .= "<input type='checkbox' onchange='javascript:tournamentEventTeammate($(this))' id='$checkbox' name='$checkbox' value='' data-timeblock='".$timeblock['timeblockID']."' $checked>";
						}
						else {
							$output .=$checked?"<div class='fa'>&#xf00c;</div>":"";
						}
						$output .="</td>";
					}
				}
				else {
					$border = isset($timeblock['border'])?$timeblock['border']:"";
					$output .= "<td style='$border background-color:".rainbow($i)."'></th>";
				}

			}
			//$output .="<td id='studenttotal-".$rowStudent['studentID']."'>$studentTotal</td></tr>"; //TODO: not necessary
			$output .="<td id='studenttotal-".$rowStudent['studentID']."'></td></tr>";
		endwhile;
	}
	else {
		exit("Make sure to add students to this team before this step!");
	}

	//print the total signed up for each event
	$output .="</tbody><tfoot><tr><td><strong>$totalStudents</strong> Total Teammates</td>";
	foreach ($timeblocks as $i=>$timeblock) {
		$timeEvents= $timeblock['events'];
		if($timeEvents)
		{
			foreach ($timeEvents as $timeEvent) {
				//the errorText could be removed and done in javascript at first as well as other calculations
				$errorText = "";
				/*
				if($timeEvents[$n]['eventTotal']>$timeEvents[$n]['numberStudents']){
					$errorText = "<div class='modified error'>Too MANY students!</div>";
				}
				else if($timeEvents[$n]['eventTotal']<$timeEvents[$n]['numberStudents']) {
					$errorText = "<div class='modified warning'>Too FEW students!</div>";
				}
				$border = isset($timeblocks[$i]['border'])?$timeblocks[$i]['border']:"";
				*/
				$output .= "<td data-eventmax='".$timeEvent['numberStudents']."' id='eventtotal-".$timeEvent['tournamenteventID']."' style='$border background-color:".rainbow($i)."'>".$timeEvent['eventTotal']." $errorText</td>";
			}
		}
		else {
			$border = isset($timeblock['border'])?$timeblock['border']:"";
			$output .= "<td style='$border background-color:".rainbow($i)."'></td>";
		}
	}
	$output .="</tr>";

	//print the place for each event
	$output .="<tr><td>Place</td>";
	foreach ($timeblocks as $i=>$timeblock) {
		$timeEvents= $timeblock['events'];
		if($timeEvents)
		{
			foreach ($timeEvents as $timeEvent) {
				$placeName = "placement-".$timeEvent['tournamenteventID']."--".$teamID;//do not put studentID here the -- makes this null
				$query = "SELECT * FROM `teammateplace` WHERE `tournamenteventID` =  ".$timeEvent['tournamenteventID']." AND `teamID` = $teamID";
				$resultTeammateplace = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
				$rowPlace="";
				if(mysqli_num_rows($resultTeammateplace))
				{
					$rowPlace = $resultTeammateplace->fetch_assoc();
				}
				$border = isset($timeblock['border'])?$timeblock['border']:"";
				$output .= "<td style='$border background-color:".rainbow($i)."'>";
				$place = isset($rowPlace['place'])?$rowPlace['place']:"";
				if(userHasPrivilege(3)){
					$output .= "<div><input id='$placeName' name='$placeName' type='number' min='1' max='999' onchange='javascript:tournamentEventTeammate($(this))' value='$place'/></div>";
				}
				else {
					$output .= $place;
				}
				$output .= "</td>";
			}
		}
		else {
			$border = isset($timeblock['border'])?$timeblock['border']:"";
			$output .= "<td style='$border background-color:".rainbow($i)."'></td>";
		}
	}
	$output .="</tr>";

	$output .="</tfoot></table></form>";
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
