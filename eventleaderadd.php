<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);

$year = intval($_POST['year']);
$studentID = intval($_POST['studentID']);
$eventID = intval($_POST['eventsList']);
if(empty($year)||empty($studentID)||empty($eventID))
{
	echo "Missing a required field in order to add an event leader";
	exit();
}

//Check to see if officer is already added
$query = "SELECT * FROM `eventleader` WHERE `year` = $year AND `studentID` = $studentID AND `eventID` = $eventID";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($row = $result->fetch_assoc())
{
	error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	echo "Duplicate entry of $position";
	exit();
}

//Insert event
$query = "INSERT INTO `eventleader` (`studentID`, `year`, `eventID`) VALUES ($studentID, $year, $eventID) ";
if ($mysqlConn->query($query) === TRUE)
{
	echo $mysqlConn->insert_id;
}
else
{
	error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	echo "Insert of $studentID as a leader of $eventID failed.";
}
?>
