<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");

//check for permissions to add/edit an event
if($_SESSION['userData']['privilege']<3 )
{
	echo "You do not have permissions to add/edit an event.";
	exit();
}

$year = intval($_POST['year']);
if(empty($year))
{
	$year = getCurrentSOYear();
}

$query .= "SELECT * from `eventyear` t1 INNER JOIN `event` t2 ON t1.`event`= t2.`event` LEFT JOIN `student` t3 ON t1.`studentID`= t3.`studentID` WHERE t1.`year` LIKE '$year' ORDER BY t1.`event` ASC ";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$events = "";
while ($row = $result->fetch_assoc()):
	$leaderStr = "Add";
	$leaderName = "";
	if($row['studentID'])
	{
		$leaderStr ="Edit";
		$leaderName = ", " . $row["first"] . " " . $row["last"];
	}
	$events .= "<div id='eventyear-".$row["eventID"]."'>".$row["event"]." - ". $row["type"] ."$leaderName <a href='javascript:eventYearLeader(\"".$row["eventID"]."\")'>$leaderStr Leader</a> <a href='javascript:eventYearRemove(\"".$row["eventID"]."\")'>Remove Event</a></div>";
endwhile;
?>
<form id="addTo" method="post" action="eventyearadd.php">
	<p>
		<label for="year">Year</label>
		<?=getSOYears($year)?>
	</p>
	<div id="eventsP">
		<?=$events?>
	</div>
	<p>
		<?php include("eventsselectb.php");?>
	</p>
	<p>
		<input class="button" type="button" onclick="window.location='#events'" value="Return" />
		<input class="submit" type="submit" value="Add">
	</p>
</form>
