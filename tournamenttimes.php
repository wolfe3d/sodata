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
	$output ="<h2>Available Blocks</h2>";
	$output .="<ol id='timeblocks'>";
	$query = "SELECT * FROM `timeblock` WHERE `tournamentID` = $tournamentID ORDER BY `timeStart`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(mysqli_num_rows($result))
	{
		$i=0;
		while ($row = $result->fetch_assoc()):
			$output .= "<li id='timeblock-".$row['timeblockID']."'>" . timeblockEdit($row['timeblockID'],date("Y:m:d G:i",strtotime($row["timeStart"])) ." - " . date("Y:m:d G:i",strtotime($row["timeEnd"])),(userHasPrivilege(3))) . " <a class='fa' href='javascript:tournamentTimeblockRemove(". $row['timeblockID'] .")'>&#xf00d; Remove</a>  </li>";
			$i+=1;
			if ($i>11) $i=0;
		endwhile;
	}
	else
	{
		$output .="None Added";
	}
	$output .="</ol><br>";
	echo $output;
}
?>
<div id='myTitle'><?=$tournamentRow['tournamentName']?> - <?=$tournamentRow['year']?></div>
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
		<button class='btn btn-outline-secondary' onclick='window.history.back()'><span class='fa fa-arrow-circle-left'></span> Return</button>
		<input class="submit fa" type="submit" value="&#xf067; Add">
	</p>
</form>
