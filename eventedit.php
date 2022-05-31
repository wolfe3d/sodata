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
<form id="addTo" method="post" action="">
	<fieldset>
		<legend>Edit Event</legend>
		<?php require_once  ("eventform.php"); ?>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()'><span class='fa fa-arrow-circle-left'></span> Return</button></p>
	</fieldset>
</form>
<?php
}
?>
