<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);

$year = intval($_POST['year']);
$studentID = intval($_POST['studentID']);
$position = $mysqlConn->real_escape_string($_POST['position']);
if(empty($year)||empty($studentID)||empty($position))
{
	echo "Missing a required field in order to add an officer";
	exit();
}

//Check to see if officer is already added
$query = "SELECT * FROM `officer` WHERE `year` = $year AND `studentID` = $studentID AND `position` LIKE '$position'";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($row = $result->fetch_assoc())
{
	error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	echo "Duplicate entry of $position";
	exit();
}

//Insert event
$query = "INSERT INTO `officer` (`studentID`, `year`, `position`) VALUES ($studentID, $year, '$position') ";
if ($mysqlConn->query($query) === TRUE)
{
	echo $mysqlConn->insert_id;
}
else
{
	error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	echo "Insert of $eventName failed.";
}
?>
