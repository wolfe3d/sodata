<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$year = intval($_POST['year']);
$eventID = intval($_POST['eventsList']);
if(empty($year)||empty($eventID))
{
	echo "Missing the event or year.  Cannot add to database.";
	exit;
}

//Check to see if event is already added
$query = "SELECT * FROM `eventyear` INNER JOIN `event` ON `eventyear`.`eventID`=`event`.`eventID` WHERE `year` = $year AND `eventyear`.`eventID` = $eventID ";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($row = $result->fetch_assoc())
{
	error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	echo "Duplicate entry of ".$row['event'];
	exit();
}

//Insert event
$query = "INSERT INTO `eventyear` (`eventID`, `year`) VALUES ('$eventID', $year) ";
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
