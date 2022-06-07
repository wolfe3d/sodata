<?php
require_once  ("php/functions.php");
userCheckPrivilege(3);

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
		//Check for the number of teams created
		$query = "SELECT `numberTeams` FROM `tournament` WHERE `tournamentID` = $tournamentID";
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
			echo "<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>";
			exit();
		}
	}
	else {
		echo "<div style='color:red'>tournamentID is not set.</div>";
		exit();
	}
$query = "INSERT INTO `team` (`tournamentID`, `teamName`) VALUES ( '$tournamentID', '$teamName');";
}
if(empty($teamName))
{
	//no event id was sent, so initiate adding an event
	echo "<div style='color:red'>No team name was sent.</div>";
	exit();
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
