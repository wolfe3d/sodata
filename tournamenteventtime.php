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

//Get tournament row information
$query = "SELECT * FROM `tournament` WHERE `tournamentID` = $tournamentID";
$resultTournament = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$tournamentRow = $resultTournament->fetch_assoc();

//find all teams
$queryTeam = "SELECT * FROM `team` WHERE `tournamentID` = $tournamentID";
$resultTeam = $mysqlConn->query($queryTeam) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$teams = [];
if(mysqli_num_rows($resultTeam)){
	while ($rowTeam = $resultTeam->fetch_assoc()):
		array_push($teams, $rowTeam);
	endwhile;
	$output .="<div id='teams' style='display:none'>".json_encode($teams)."</div>";
}

//Get tournament times
$query = "SELECT * FROM `timeblock` WHERE `tournamentID` = $tournamentID ORDER BY `timeStart`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if(mysqli_num_rows($result))
{
	$output .="<h2>Choose Times</h2><div id='note'></div><div>For all testing events, make sure to choose the same time block for all teams.  Build events can be at different times.</div>";
	$output .="<form id='timeForm' method='post' action='tournamentChangeMe.php'><table class='tournament table table-hover'><thead>";
	$timeblocks = [];
	while ($row = $result->fetch_assoc()):
		array_push($timeblocks, $row);
	endwhile;

	//Run through times and figure out the number of different dates and print columns with colspan of times for that date
	$output .="<tr><th rowspan='2' style='vertical-align:bottom;'>Events</th>";
	$dateCheck = "";
	$dateColSpan = 1;
	$dateCount = 0;
	for ($i = 0; $i < count($timeblocks); $i++) {
		if($dateCheck==""){
			$dateCheck=date("F j, Y",strtotime($timeblocks[$i]["timeStart"]));
			$timeblocks[$i]['border']="";
		}
		else {
			if($dateCheck!=date("F j, Y",strtotime($timeblocks[$i]["timeStart"]))){
				$output .= "<th colspan='$dateColSpan' style='border-right:2px solid black; text-align:center;'>" . $dateCheck . "</th>";
				$dateCheck=date("F j, Y",strtotime($timeblocks[$i]["timeStart"]));
				$dateColSpan = 1;
				$dateCount +=1;
				$timeblocks[$i]['border'] = "border-left:2px solid black; "; //adds border at beginning of new date
			}
			else {
				$dateColSpan += 1;
				$timeblocks[$i]['border'] = "";
			}
		}
	}
	$output .= "<th colspan='$dateColSpan' style='text-align:center;'>" . $dateCheck . "</th>";
	$output .="</tr>";

//print the time for each event and date
	$output .="<tr>";
	for ($i = 0; $i < count($timeblocks); $i++) {
		$border = isset($timeblocks[$i]['border'])?$timeblocks[$i]['border']:"";
		$output .= "<th id='timeblock-".$timeblocks[$i]['timeblockID']."' style='$border background-color:".rainbow($i)."'>" . date("g:i A",strtotime($timeblocks[$i]["timeStart"])) ." - " . date("g:i A",strtotime($timeblocks[$i]["timeEnd"]))  . "</th>";
	}
	$output .="</tr></thead><tbody id='eventBody'";

	$queryEvent = "SELECT * FROM `tournamentevent` INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID` WHERE `tournamentID` = $tournamentID ORDER BY `event`.`event` ASC";
	$resultEvent = $mysqlConn->query($queryEvent) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(mysqli_num_rows($resultEvent))
	{
		while ($rowEvent = $resultEvent->fetch_assoc()):
			$output .= "<tr id='tournamentevent-".$rowEvent['tournamenteventID']."'><td><span id='tournamenteventname-".$rowEvent['tournamenteventID']."'>" . $rowEvent["event"] ."</span> <span id='tournamenteventwarning-".$rowEvent['tournamenteventID']."' class='error'></span></td>";
			for ($i = 0; $i < count($timeblocks); $i++) {
				//find available times  //TODO: Consider storing the query below in the timeblocks array above to reduce calls to database
					$queryEventTime = "SELECT * FROM `tournamenttimeavailable` WHERE `tournamenteventID` =  ".$rowEvent['tournamenteventID']." AND `timeblockID` = ".$timeblocks[$i]['timeblockID'];
					$resultEventTime = $mysqlConn->query($queryEventTime) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
					$border = isset($timeblocks[$i]['border'])?$timeblocks[$i]['border']:"";
					$output .= "<td style='$border background-color:".rainbow($i)."'>";
					if(mysqli_num_rows($resultEventTime)){
						//run through all teams
						for ($t = 0; $t < count($teams); $t++) {
								//print a checkbox for each team
								$checkbox = "tournamenttimechosen-".$rowEvent['tournamenteventID']."-".$teams[$t]['teamID']."-".$timeblocks[$i]['timeblockID'];
								$queryEventTimeChosen = "SELECT * FROM `tournamenttimechosen` WHERE `tournamenteventID` =  ".$rowEvent['tournamenteventID']." AND `timeblockID` = ".$timeblocks[$i]['timeblockID'] . " AND `teamID` = ".$teams[$t]['teamID'];
								$resultEventTimeChosen = $mysqlConn->query($queryEventTimeChosen) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
								//if there is a result then make box checked, if not do not check box.
								$checked = mysqli_num_rows($resultEventTimeChosen)?" checked ":"";
						    $output .= "<input type='checkbox' onchange='javascript:tournamentEventTimeSet($(this))' id='$checkbox' name='$checkbox' value='' $checked><label for='$checkbox'>".$teams[$t]['teamName']."</label>";
						}
					}
					$output .= "</td>";
				}
			$output .= "</tr>";
		endwhile;
	}
	else {
		exit("<input class='button fa' type='button' onclick='javascript:tournamentEventsAddAll($tournamentID,".$tournamentRow['year'].")' value='&#xf0c3; Add all events from this year' />");
	}
	$output .="</tbody></table></form>";
}
else {
	exit("<div>Set available time blocks first!</div>");
}
echo $output;
?>
<br>
<div id='myTitle'><?=$tournamentRow['tournamentName']?> - <?=$tournamentRow['year']?></div>
<form id="addTo" method="post" action="tournamenteventadd.php">
<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='fa fa-arrow-circle-left'></span> Return</button></p>
</form>
