<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);
//TODO: Check that you have permission for this schoolID to add

$tournamentID = intval($_POST['myID']);
$teamName = $mysqlConn->real_escape_string($_POST['teamName']);
if (isset($_POST['teamID']))
{
	$teamID = intval(getIfSet($_POST['teamID']));
	$query = "UPDATE `team` SET `team`.`teamName` = '$teamName' WHERE `team`.`teamID` = $teamID";
}
else {
	if($tournamentID)
	{
		//Get number of teams created
		$query = "SELECT * FROM `team` WHERE `tournamentID` = $tournamentID";
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$amountOfCreatedTeams = $result->num_rows;
	}
	else {
		exit( "<div style='color:red'>tournamentID is not set.</div>");
	}
$query = "INSERT INTO `team` (`tournamentID`, `teamName`) VALUES ( '$tournamentID', '$teamName');";
}
if(empty($teamName))
{
	//no event id was sent, so initiate adding an event
	exit("<div style='color:red'>No team name was sent.</div>");
}

$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if ($result)
{
	exit("1");
}
else
{
	exit("Unspecified error. Check database log.");
}
?>
