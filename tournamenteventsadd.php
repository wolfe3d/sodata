<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$tournamentID = intval($_POST['myID']);
if(empty($tournamentID)){
	exit("<div style='color:red'>tournamentID is not set.</div>");
}

$eventID = intval($_POST['eventsList']);
if(empty($eventID)){
	exit("<div style='color:red'>eventID was not set.</div>");
}
//check to make sure event doesn't already exist
$query = "SELECT * FROM `tournamentevent` WHERE `eventID`=$eventID AND `tournamentID`=$tournamentID;";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($result&&mysqli_num_rows($result))
{
	exit("<div style='color:red'>Event already exists!</div>");
}


//add event
$query = "INSERT INTO `tournamentevent` (`eventID`, `tournamentID`) VALUES ($eventID, $tournamentID);";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if ($result)
{
	echo $mysqlConn->insert_id;
}
else
{
	echo $query . " " . $mysqlConn->error;
}

?>
