<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

$event = $mysqlConn->real_escape_string($_POST['event_name']);
$type = $mysqlConn->real_escape_string($_POST['type']);


$queryInsert = "INSERT INTO `event` (`event`, `type`) VALUES ( '$event', '$type');";
if ($mysqlConn->query($queryInsert) === TRUE)
{
	echo "New record created.\n";
	include("events.php");
}
else
{
	echo json_encode(array("error"=>"Error_addEvent: $queryInsert $mysqlConn->error"));
}
?>
