<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges


//text output
$output = "";

$eventsChoiceID = intval($_REQUEST['eventChoiceID']);

$query = "DELETE FROM `eventchoice` WHERE `eventchoice`.`eventChoiceID` = $eventsChoiceID";
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
