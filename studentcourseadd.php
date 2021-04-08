<?php
require_once ("../connectsodb.php");
//text output
$output = "";

$studentID = intval($_REQUEST['studentID']);
$courseID = intval($_REQUEST['courseID']);
$tableName = strtolower ($mysqlConn->real_escape_string($_REQUEST['tableName']));

$query = "INSERT INTO `$tableName` (`myID`, `studentID`, `courseID`) VALUES (NULL, '$studentID', '$courseID') ";
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
