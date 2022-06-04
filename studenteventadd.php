<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(2);
//text output
$output = "";

$studentID = intval($_REQUEST['studentID']);
$eventyearID = intval($_REQUEST['eventyearID']);
$priority = intval($_REQUEST['priority']);

$query = "INSERT INTO `eventchoice` (`studentID`, `eventyearID`, `priority`) VALUES ('$studentID', '$eventyearID', '$priority') ";
if ($mysqlConn->query($query) === TRUE)
{
	echo $mysqlConn->insert_id;
}
else
{
	error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	echo "0";
}
?>
