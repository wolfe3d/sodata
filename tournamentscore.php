<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
require_once  ("functions.php");

function checkPlacements($db,$tournamentID)
{
	//Get teammateplace
	$query = "SELECT `teammateplace`.`studentID` FROM `teammateplace` INNER JOIN `team` ON `teammateplace`.`teamID` = `team`.`teamID` WHERE `team`.`tournamentID` = $tournamentID";
	$resultTournament = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($resultTournament->num_rows){
		echo "33". $resultTournament->num_rows;
		return TRUE;
	}
	return FALSE;
}

$output = "";
$tournamentID = intval($_POST['myID']);
if(empty($tournamentID))
{
	echo "<div style='color:red'>teamID is not set.</div>";
	exit();
}

//Get team and tournament row information
$query = "SELECT * FROM `score` INNER JOIN `tournament` ON `score`.`tournamentID` = `tournament`.`tournamentID` WHERE `tournamentID` = $tournamentID";
$resultTournament = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");


$output .="<h2>Tournament Teammate Placement and Score</h2>";
if($resultTournament)
{
	$rowTournament = $resultTournament->fetch_assoc();
	//TODO: Add recalculate button here
	$output .="add stuff here";
}
else {
	$output .="<p>Data has not been calculated or placements are not available.</p>";
	if(userHasPrivilege(3)){
		if(checkPlacements($mysqlConn, $tournamentID))
		{
			$output .="Attempt Calculation";
		}
		else {
				$output .="Enter Placements in Assign Events Page";
		}

	}
	else{
		$output .="Ask Coach to choose calculate.";
	}
}

echo $output;
?>
<br>
<form id="addTo" method="post" action="tournamenteventadd.php">
	<p>
		<input class="button" type="button" onclick="window.history.back()" value="Return" />
	</p>
</form>
