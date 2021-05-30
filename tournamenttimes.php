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
	if(mysqli_num_rows($result))
	{
		$output .="<h2>Available Blocks</h2>";
		$output .="<div>";
		$i=0;
		while ($row = $result->fetch_assoc()):
			$output .= "<div id='timeblock-".$row['timeblockID']."' style='background-color:".rainbow($i)."'>" . $row["timeStart"] . " - ". $row["timeEnd"] . " <a href='javascript:tournamentTimeblockRemove(". $row['timeblockID'] .",\"timeblock\")'>Remove</a></div>";
			$i+=1;
			if ($i>11) $i=0;
		endwhile;
		$output .="</div><br>";
	}
	echo $output;
}
?>

<h2>Add Time Block</h2>
<p>Make sure you use the time that you are competing, so this simplifies the user's plan.  If you are competing locally, then use the time as given.  If you are competing locally in EST in a remote competition that takes place in PST, then convert the times to EST. If you are traveling to competition in the PST, use the PST times given.</p>
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
