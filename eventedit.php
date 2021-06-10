<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$eventID = intval($_POST['myID']);
$typeName = "";
if(isset($eventID))
{
	$query = "SELECT * FROM `event` WHERE `eventID` = $eventID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row = $result->fetch_assoc();
	$typeName = $row["type"];
	?>
<form id="addTo" method="post" action="eventeditadjust.php">
	<fieldset>
		<legend>Edit Event</legend>
		<?php require_once  ("eventform.php"); ?>
		<p>
			<input class="button fa" type="button" onclick="window.history.back()" value="&#xf0a8; Return" />
		</p>
	</fieldset>
</form>
<?php
}
?>
