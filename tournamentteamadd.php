<?php
require_once  ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$tournamentID = intval($_POST['myID']);
$alphabet = range('A', 'Z');
if($tournamentID)
{
		//Check for the number of teams created
		$query = "SELECT `tournamentName`,`year` FROM `tournament` WHERE `tournamentID` = $tournamentID";
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$row = $result->fetch_assoc();

		//Get number of teams created
		$query = "SELECT * FROM `team` WHERE `tournamentID` = $tournamentID";
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$amountOfCreatedTeams = $result->num_rows;
}
?>
<div id='myTitle'><?=$row['tournamentName']?> - <?=$row['year']?></div>

<form id="addTo" method="post" action="tournamentteaminsert.php">
	<p id="teamName">
		<label for="teamName">Team Name</label>
		<input id="teamName" name="teamName" type="text" value="<?=$alphabet[$amountOfCreatedTeams]?>">
		<input id="myID" type="hidden" name="myID" value="<?=$tournamentID?>">
	</p>
	<p>
		<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Cancel</button>
		<button class="btn btn-primary" onclick='addToSubmit("tournamentteaminsert.php")' type="button"><span class='bi bi-save'></span> Save</button>
	</p>
</form>
