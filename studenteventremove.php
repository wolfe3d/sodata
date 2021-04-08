<?php
require_once ("../connectsodb.php");
//text output
$output = "";

$eventsChoiceID = intval($_REQUEST['eventsChoiceID']);

$query = "DELETE FROM `eventschoice` WHERE `eventschoice`.`eventsChoiceID` = $eventsChoiceID";
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
