<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

//text output
$output = "";

$myID = intval($_REQUEST['myCourseID']);
$tableName = strtolower($mysqlConn->real_escape_string($_REQUEST['tableName']));

$query = "DELETE FROM `$tableName` WHERE `$tableName`.`myID` = $myID";
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
