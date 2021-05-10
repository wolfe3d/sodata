<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$eventID = intval($_POST['eventID']);
$eventName = $mysqlConn->real_escape_string($_POST['eventName']);
$typeName = $mysqlConn->real_escape_string($_POST['typeName']);

if(empty($eventName))
{
	//no event id was sent, so initiate adding an event
	echo "No event name was sent.";
	exit();
}

if(empty($eventID)){
	$query = "INSERT INTO `event` (`event`, `type`) VALUES ( '$eventName', '$typeName');";
}
else {
	//update the event
	$query = "UPDATE `event` SET `event`.`event` = '$eventName', `event`.`type` = '$typeName' WHERE `event`.`eventID` = $eventID";
}
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if ($result)
{
	echo "1";
}
else
{
	echo $mysqlConn->error;
}
?>
