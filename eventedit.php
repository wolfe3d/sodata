<?php
require_once  ("php/functions.php");
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
	<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>
	</fieldset>
</form>
<?php
}
?>
