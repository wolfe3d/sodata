<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
require_once  ("functions.php");

$output = "";
$tournamenteventID = intval($_POST['myID']);
if(empty($tournamenteventID))
{
	echo "<div style='color:red'>tournamenteventID is not set.</div>";
	exit();
}

//TODO make one query

//Get tournamentevent row information
$query = "SELECT * FROM `tournamentevent` WHERE `tournamenteventID` = $tournamenteventID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$row = $result->fetch_assoc();

//get tournament information
$query = "SELECT * FROM `tournament` WHERE `tournamentID` = ". $row['tournamentID'];
$resultTournament = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$rowTournament = $resultTournament->fetch_assoc();

//get event information
$query = "SELECT * FROM `event` WHERE `eventID` = ". $row['eventID'];
$resultEvent = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$rowEvent = $resultEvent->fetch_assoc();


echo $output;
?>
<br>
<div id='myTitle'>View Event Note</div>
		<p><?=$rowEvent['event']?> at <?=$rowTournament['tournamentName']?></p>
	<form id="addTo" method="post" action="tournamentUpdate.php">
			<label for="note">Event Note</label>
			<input id="note" name="note" type="text" value="<?=$row['note']?>">
	</form>
	<input class="button fa" type="button" onclick="window.history.back()" value="&#xf0a8; Return" />
</div>
