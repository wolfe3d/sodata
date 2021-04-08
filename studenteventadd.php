<?php
require_once ("../connectsodb.php");
//text output
$output = "";

$studentID = intval($_REQUEST['studentID']);
$eventID = intval($_REQUEST['eventID']);
$priority = intval($_REQUEST['priority']);

$query = "INSERT INTO `eventschoice` (`eventsChoiceID`, `studentID`, `eventID`, `priority`) VALUES (NULL, '$studentID', '$eventID', '$priority') ";
if ($mysqlConn->query($query) === TRUE)
{
	echo "1";
}
else
{
	error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	echo "0";
}
?>
