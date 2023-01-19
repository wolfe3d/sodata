<?php
require_once  ("php/functions.php");
userCheckPrivilege(3);
$schoolID =$_SESSION['userData']['schoolID'] ;

$output = "";
$teamID = intval($_POST['myID']);
if(empty($teamID))
{
	echo "<div style='color:red'>teamID is not set.</div>";
	exit();
}
//Get team and tournament row information
$query = "SELECT * FROM `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID` WHERE `teamID` = $teamID AND `schoolID` = $schoolID";
$resultTeam = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$rowTeam = $resultTeam->fetch_assoc();


//$timeblocks = makeTimeArray($mysqlConn, $rowTeam['tournamentID']);
//$events = getEventsTable($mysqlConn);

echo "<h2><span id='myTitle'>".$rowTeam['tournamentName'].": ".$rowTeam['teamName']."</span></h2><div id='note'></div>";

echo "<p>teamID=$teamID; tournamentID: <span id='tournamentID'>".$rowTeam['tournamentID']."<span></p>";
?>
<form>
<p><input type="checkbox" id="thisYear" name="thisYear" value="thisYear" checked><label for="thisYear"> Use only this year's Data</label></p>
</form>
<p><a class='btn btn-info' role='button' href='javascript:proposeByScore(<?=$teamID?>)'><span class='bi bi-graph-up'></span> Propose By Top Score Team</a></p>
<p id="topScore"></p>
<p><a class='btn btn-info' role='button' href='javascript:proposeByBruteForce(<?=$teamID?>)'><span class='bi bi-graph-up'></span> Propose Brute Force Team</a></p>
<p id="bruteForce"></p>
<p><a class='btn btn-info' role='button' href='javascript:proposeByAllForce(<?=$teamID?>)'><span class='bi bi-graph-up'></span> Propose Using All Force Team</a></p>
<p id="allForce"></p>
<?php include  ("tournamentteamassign.php");?>

<!--<script src="js/teampropose.js"></script>-->
