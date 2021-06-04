<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
require_once  ("functions.php");

$eventID = intval($_POST['myID']);
$typeName = "";
if(isset($eventID))
{
	$query = "SELECT * FROM `event` WHERE `eventID` = $eventID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row = $result->fetch_assoc();
	$typeName = $row["type"];
}
?>
<form id="addTo" method="post" action="eventeditadjust.php">
	<?php if($row){ ?>
			<input id="eventID" name="eventID" type="hidden" value="<?=$row["eventID"]?>">
	<?php } ?>
	<p>
		<label for="eventName">Event</label>
		<input id="eventName" name="eventName" type="text" value="<?=$row["event"]?>">
	</p>
	<p>
		<label for="typeName">Event Type</label>
		<?=getEventTypes($mysqlConn,$typeName)?>
	</p>
	<p>
		<input class="button" type="button" onclick="window.location='#events'" value="Cancel" />
		<input class="submit" type="submit" value="Submit">
	</p>
</form>
