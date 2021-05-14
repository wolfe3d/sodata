<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
require_once  ("functions.php");


$year = intval($_POST['year']);
if(empty($year))
{
	$year = getCurrentSOYear();
}

$query .= "SELECT * from `eventyear` INNER JOIN `event` ON `eventyear`.`eventID`= `event`.`eventID` LEFT JOIN `student` ON `eventyear`.`studentID`= `student`.`studentID` WHERE `eventyear`.`year` LIKE '$year' ORDER BY `event`.`event` ASC ";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$events = "";
while ($row = $result->fetch_assoc()):
	$leaderStr = "Add";
	$leaderName = "";
	if($row['studentID'])
	{
		$leaderStr ="Edit";
		$leaderName = " - " . $row["last"] . ", " . $row["first"];
	}
	$events .= "<div id='eventyear-".$row["eventyearID"]."'><span class='event'>".$row["event"]." - ". $row["type"] ."</span> <span class='eventleader' data-id='".$row['studentID']."'>$leaderName</span> <a id='leaderlink-".$row["eventyearID"]."' href='javascript:eventYearLeader(\"".$row["eventyearID"]."\")'>$leaderStr Leader</a> <a href='javascript:eventYearRemove(\"".$row["eventyearID"]."\")'>Remove Event</a></div>";
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
		<input class="button fa" type="button" onclick="window.location='#events'" value="&#xf0a8; Return" />
		<input class="submit fa" type="submit" value="&#xf067; Add">
	</p>
</form>
