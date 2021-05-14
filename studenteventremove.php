<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(2);

//text output
$output = "";

$eventChoiceID = intval($_REQUEST['eventChoiceID']);

$query = "DELETE FROM `eventchoice` WHERE `eventchoice`.`eventChoiceID` = $eventChoiceID";
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
