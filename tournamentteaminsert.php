<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$tournamentID = intval($_POST['myID']);
$teamID = intval($_POST['teamID']);
$teamName = $mysqlConn->real_escape_string($_POST['teamName']);
if($tournamentID)
{
	//Check for the number of teams created
	$query = "SELECT * FROM `tournament` WHERE `tournamentID` = $tournamentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row = $result->fetch_assoc();
	$numberTeams = $row["numberTeams"];

	//Get number of teams created
	$query = "SELECT * FROM `team` WHERE `tournamentID` = $tournamentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$amountOfCreatedTeams = $result->num_rows;
	//if there is already enough teams created, then do not allow an increase in team number
	if($amountOfCreatedTeams>=$numberTeams)
	{
		echo "<div style='color:red'>The maximum number of teams has been created.</div>";
		echo "<div><input class='button' type='button' onclick='window.history.back()' value='Return' /></div>";
		exit();
	}
}
else {
	echo "<div style='color:red'>tournamentID is not set.</div>";
	exit();
}

if(empty($teamName))
{
	//no event id was sent, so initiate adding an event
	echo "<div style='color:red'>No team name was sent.</div>";
	exit();
}

if(empty($teamID)){
	$query = "INSERT INTO `team` (`tournamentID`, `teamName`) VALUES ( '$tournamentID', '$teamName');";
}
else {
	//update the event
	$query = "UPDATE `team` SET `team`.`teamName` = '$teamName' WHERE `team`.`teamID` = $teamID";
}
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if ($result)
{
	echo "1";
}
else
{
	echo $mysqlConn->error;
}
?>
