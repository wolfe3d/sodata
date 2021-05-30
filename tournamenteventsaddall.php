<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$tournamentID = intval($_POST['tournamentID']);
if(empty($tournamentID))
{
	echo "<div style='color:red'>tournamentID is not set.</div>";
	exit();
}

$year = intval($_POST['year']);
if(!$year){
	echo "<div style='color:red'>Year was not set.</div>";
	exit();
}

/*find events in year*/
$yearQuery = "SELECT `eventID` FROM `eventyear` WHERE `year` = $year";
$resultYear = $mysqlConn->query($yearQuery) or print("\n<br />Warning: query failed: $query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$eventIDs = "";
while ($row = $resultYear->fetch_assoc()):
	//make array of results
	$eventIDs .= $eventIDs!=""?", ":"";
	$eventIDs .= "('".$row['eventID']."','$tournamentID')";
endwhile;

//No events found
if($eventIDs  =="")
{
	echo "There are no events in the year $year.";
	exit();
}

$query = "INSERT INTO `tournamentevent` (`eventID`, `tournamentID`) VALUES $eventIDs;";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if ($result)
{
	echo "1";
}
else
{
	echo $query . " " . $mysqlConn->error;
}

?>
