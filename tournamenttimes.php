<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
require_once  ("functions.php");


$tournamentID = intval($_POST['myID']);
if($tournamentID)
{
	//Check for the number of teams created
	$query = "SELECT * FROM `tournament` WHERE `tournamentID` = $tournamentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$tournamentRow = $result->fetch_assoc();

	//Get number of teams created
	$query = "SELECT * FROM `timeblock` WHERE `tournamentID` = $tournamentID ORDER BY `timeStart`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = "";
	if($result)
	{
		$output .="<h2>Available Blocks</h2>";
		$output .="<div>";
		$i=0;
		while ($row = $result->fetch_assoc()):
			$output .= "<div style='background-color:".rainbow($i)."'>" . $row["timeStart"] . " - ". $row["timeEnd"] . " <a href='javascript:tournamentTimeblockRemove(". $row['blockID'] .")'>Remove</a></div>";
			$i+=1;
			if ($i>11) $i=0;
		endwhile;
		$output .="</div><br>";
	}
	echo $output;
}
?>

<h2>Add Time Block</h2>
<form id="addTo" method="post" action="tournamenttimeadd.php">
	<p>
		<label for="timeStart">Start Time</label>
		<input id="timeStart" name="timeStart" type="datetime-local" value="<?=$tournamentRow["dateTournament"]?>T00:00"/>
	</p>
	<p>
		<label for="timeEnd">End Time</label>
		<input id="timeEnd" name="timeEnd" type="datetime-local" value="<?=$tournamentRow["dateTournament"]?>T00:00"/>
	</p>
	<p>
		<input class="button" type="button" onclick="window.history.back()" value="Cancel" />
		<input class="submit" type="submit" value="Add">
	</p>
</form>
