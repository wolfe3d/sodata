<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$event = $mysqlConn->real_escape_string($_POST['event']);
$type = $mysqlConn->real_escape_string($_POST['type']);
$calculatorType = intval($_POST['calculatorType']);
$goggleType = intval($_POST['goggleType']);
$numberStudents = intval($_POST['numberStudents']);
$sciolyLink = $mysqlConn->real_escape_string($_POST['sciolyLink']);
$description = $mysqlConn->real_escape_string($_POST['description']);

if(empty($event))
{
	//no event id was sent, so initiate adding an event
	echo "No event name was sent.";
	exit();
}

$query = "INSERT INTO `event` (`event`, `type`, `calculatorType`,`goggleType`,`numberStudents`,`sciolyLink`,`description`) VALUES ( '$event', '$type', '$calculatorType','$goggleType','$numberStudents','$sciolyLink','$description');";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if ($result)
{
	echo $mysqlConn->insert_id;
}
else {
	exit("Failed to add new event.". $mysqlConn->error);
}
?>
