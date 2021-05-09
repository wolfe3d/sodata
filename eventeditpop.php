<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");

//check for permissions to add/edit an event
if($_SESSION['userData']['privilege']<3 )
{
	echo "You do not have permissions to add/edit an event.";
	exit();
}

$eventName = $mysqlConn->real_escape_string($_POST['eventName']);
$typeName = "";
if(isset($eventName))
{
	$query = "SELECT * FROM `event` WHERE `event` LIKE '$eventName'";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row = $result->fetch_assoc();
	$typeName = $row["type"];
}
?>
<form id="addTo" method="post" action="eventedit.php">
	<?php if($row){ ?>
			<input id="eventOriginalName" name="eventOriginalName" type="hidden" value="<?=$row["event"]?>">
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
