<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

if($_SESSION['userData']['privilege']<3 )
{
	echo "You do not have permissions to add/edit an event.";
	exit();
}

$year = intval($_POST['year']);
$eventName = $mysqlConn->real_escape_string($_POST['eventsList']);
if(empty($year)||empty($eventName))
{
	echo "Missing the event or year.  Cannot add to database.";
	exit;
}

//Check to see if event is already added
$query = "SELECT * FROM `eventyear` WHERE `year` = $year AND `event` LIKE '$eventName' ";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($row = $result->fetch_assoc())
{
	error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	echo "Duplicate entry of $eventName";
	exit();
}

//Insert event
$query = "INSERT INTO `eventyear` (`event`, `year`) VALUES ('$eventName', $year) ";
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
