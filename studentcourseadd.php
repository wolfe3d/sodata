<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(2);

//text output
$output = "";
$studentID = intval($_REQUEST['studentID']);
$courseID = intval($_REQUEST['courseID']);
$tableName = strtolower ($mysqlConn->real_escape_string($_REQUEST['tableName']));

$query = "INSERT INTO `$tableName` (`studentID`, `courseID`) VALUES ('$studentID', '$courseID') ";
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
