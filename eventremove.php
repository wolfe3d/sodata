<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

if($_SESSION['userData']['privilege']<3 )
{
	echo "You do not have permissions to add/edit an event.";
	exit();
}

$eventID = intval($_REQUEST['eventID']);
if(empty($eventID))
{
	echo "Missing the eventID.  Cannot remove from database.";
	exit;
}

$query = "DELETE FROM `eventyear` WHERE `eventyear`.`eventID` = $eventID";
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
