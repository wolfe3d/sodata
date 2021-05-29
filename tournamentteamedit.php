<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(2);

$teamID= intval($_POST['teamID']);
if($teamID){
		$query = "SELECT * FROM `team` WHERE `teamID` = $teamID";
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$row = $result->fetch_assoc();
		$teamName = $row['teamName'];
}
else {
	echo "TeamID not set.";
}
?>
<form id="addTo" method="post" action="tournamentteaminsert.php">
	<p id="teamName">
		<label for="teamName">Team Name</label>
		<input id="teamName" name="teamName" type="text" value="<?=$teamName?$teamName:'A'?>">
	</p>
	<p>
		<input class="button" type="button" onclick="window.history.back()" value="Cancel" />
		<input class="submit" type="submit" value="Submit">
	</p>
</form>
