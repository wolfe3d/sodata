<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$tournamentID = intval($_POST['myID']);
$alphabet = range('A', 'Z');
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
			echo "<div><input class='button' type='button' onclick='window.history.back()' value='&#xf0a8; Return' /></div>";
			exit();
		}
		else {
			$teamName = null;
		}
}
?>
<div id='myTitle'><?=$row['tournamentName']?> - <?=$row['year']?></div>

<form id="addTo" method="post" action="tournamentteaminsert.php">
	<p id="teamName">
		<label for="teamName">Team Name</label>
		<input id="teamName" name="teamName" type="text" value="<?=$teamName?$teamName:$alphabet[$amountOfCreatedTeams]?>">
	</p>
	<p>
		<input class="button" type="button" onclick="window.history.back()" value="Cancel" />
		<input class="submit" type="submit" value="Submit">
	</p>
</form>
