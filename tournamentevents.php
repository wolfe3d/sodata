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

//Get tournament times
$query = "SELECT * FROM `timeblock` WHERE `tournamentID` = $tournamentID ORDER BY `timeStart`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if(mysqli_num_rows($result))
{
	$output .="<h2>Available Times</h2>";
	$output .="<form id='addTo' method='post' action='tournamenteventadd.php'>";
	$output .="<table><tr style='height:200px'><th>Events</th>";
	$timeblocks = [];
	while ($row = $result->fetch_assoc()):
		$output .= "<th id='timeblock-".$row['timeblock']."' style='transform:rotate(315deg);'>" . $row["timeStart"] ." - " . $row["timeEnd"]  . "</th>";
		array_push($timeblocks, $row['timeblockID']);
	endwhile;
	$output .="</tr>";

	$queryEvent = "SELECT * FROM `tournamentevent` INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID` WHERE `tournamentID` = $tournamentID ORDER BY `event`.`event` ASC";
	$resultEvent = $mysqlConn->query($queryEvent) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(mysqli_num_rows($resultEvent))
	{
		while ($rowEvent = $resultEvent->fetch_assoc()):
			$output .= "<tr id='tournamentevent-".$rowEvent['tournamenteventID']."'><th>" . $rowEvent["event"] ." <a href='javascript:tournamenteventRemove(". $row['tournamenteventID'] .")'>X</a></th>";
			for ($i = 0; $i < count($timeblocks); $i++) {
					$checkbox = "tournamenteventtime-".$timeblocks[$i]."-".$rowEvent['tournamenteventID'];
			    $output .= "<td id='timeblock-".$timeblocks[$i]."'><input type='checkbox' id='$checkbox' name='$checkbox' value=''></td>";
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
