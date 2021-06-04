<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
require_once  ("functions.php");

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

/*
//Get tournament events
$query = "SELECT * FROM `tournamentevent` INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID` WHERE `tournamentID` = $tournamentID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if(mysqli_num_rows($result))
{
	$output .="<h2>Events</h2>";
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$output .= "<div id='tournamentevent-".$row['tournamenteventID']."'>" . $row["event"] . " <a href='javascript:tournamenteventRemove(". $row['tournamenteventID'] .")'>Remove</a></div>";
	endwhile;
	$output .="</div>";
}
else {
	$output .="<div><input class='button fa' type='button' onclick='javascript:tournamentEventsAddAll($tournamentID,".$tournamentRow['year'].")' value='&#xf0c3; Add all events from this year' /></div>";
}
*/



//Get tournament times and selection
$query = "SELECT * FROM `timeblock` WHERE `tournamentID` = $tournamentID ORDER BY `timeStart`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if(mysqli_num_rows($result))
{
	$output .="<h2>Available Times</h2><div id='note'></div>";
	$output .="<form id='changeme' method='post' action='tournamentChangeMe.php'><table>";
	$timeblocks = [];
	while ($row = $result->fetch_assoc()):
		array_push($timeblocks, $row);
	endwhile;

//Run through times and figure out the number of different dates and print columns with colspan of times for that date
	$output .="<tr><th rowspan='2' style='vertical-align:bottom;'>Events</th>";
	$dateCheck = "";
	$dateI = 0;
	for ($i = 0; $i < count($timeblocks); $i++) {
		if($dateCheck==""){
			$dateCheck=date("F j, Y",strtotime($timeblocks[$i]["timeStart"]));
			$dateI = 1;
		}
		else {
			if($dateCheck!=date("F j, Y",strtotime($timeblocks[$i]["timeStart"]))){
				$output .= "<th colspan='$dateI' style='text-align:center;'>" . $dateCheck . "</th>";
			}
			else {
				$dateI += 1;
			}
		}
	}
	$output .= "<th colspan='$dateI' style='text-align:center;'>" . $dateCheck . "</th>";
	$output .="</tr>";

//print the time for each event and date
	$output .="<tr>";
	for ($i = 0; $i < count($timeblocks); $i++) {
		$output .= "<th id='timeblock-".$timeblocks[$i]['timeblockID']."' style='background-color:".rainbow($i)."'>" . date("g:i A",strtotime($timeblocks[$i]["timeStart"])) ." - " . date("g:i A",strtotime($timeblocks[$i]["timeEnd"]))  . "</th>";
	}
	$output .="</tr>";

	$queryEvent = "SELECT * FROM `tournamentevent` INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID` WHERE `tournamentID` = $tournamentID ORDER BY `event`.`event` ASC";
	$resultEvent = $mysqlConn->query($queryEvent) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(mysqli_num_rows($resultEvent))
	{
		while ($rowEvent = $resultEvent->fetch_assoc()):
			$output .= "<tr><th><span id='tournamentevent-".$rowEvent['tournamenteventID']."'>" . $rowEvent["event"] ."</span> <a href='javascript:tournamenteventRemove(". $row['tournamenteventID'] .")'>X</a></th>";
			for ($i = 0; $i < count($timeblocks); $i++) {
					$checkbox = "tournamenttimeavailable-".$rowEvent['tournamenteventID']."-".$timeblocks[$i]['timeblockID'];
					$queryEventTime = "SELECT * FROM `tournamenttimeavailable` WHERE `tournamenteventID` =  ".$rowEvent['tournamenteventID']." AND `timeblockID` = ".$timeblocks[$i]['timeblockID'];
					$resultEventTime = $mysqlConn->query($queryEventTime) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
//if there is a result then make box checked, if not do not check box.
					$checked = mysqli_num_rows($resultEventTime)?" checked ":"";
			    $output .= "<td style='background-color:".rainbow($i)."'><input type='checkbox' onchange='javascript:tournamentEventTimeSet($(this))' id='$checkbox' name='$checkbox' value='' $checked></td>";
			}
			$output .= "</tr>";
		endwhile;
	}
	else {
		exit("<input class='button fa' type='button' onclick='javascript:tournamentEventsAddAll($tournamentID,".$tournamentRow['year'].")' value='&#xf0c3; Add all events from this year' />");
	}
	$output .="</table></form>";
}
else {
	exit("<div>Set available time blocks first!</div>");
}
echo $output;
?>
<br>
<div id='myTitle'><?=$tournamentRow['tournamentName']?> - <?=$tournamentRow['year']?></div>

<h2>Add Other Events</h2>
<form id="addTo" method="post" action="tournamenteventadd.php">
	<p>
		<?php include("eventsselectb.php");?>
	</p>
	<p>
		<input class="button" type="button" onclick="window.history.back()" value="Cancel" />
		<input class="submit" type="submit" value="Add">
	</p>
</form>