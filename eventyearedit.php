<?php
require_once  ("php/functions.php");
userCheckPrivilege(3);

$year = getIfSet($_REQUEST['myID'],getCurrentSOYear());
$query = "SELECT * from `eventyear` INNER JOIN `event` ON `eventyear`.`eventID`= `event`.`eventID` LEFT JOIN `student` ON `eventyear`.`studentID`= `student`.`studentID` WHERE `schoolID`= " .$user->schoolID . " AND `eventyear`.`year` LIKE '$year' ORDER BY `event`.`event` ASC ";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$events = "";
while ($row = $result->fetch_assoc()):
	$leaderStr = "Add";
	$leaderName = "";
	if($row['studentID'])
	{
		$leaderStr ="Edit";
		$leaderName = " - " . $row["last"] . ", " . $row["first"];
	}
	$events .= "<div id='eventyear-".$row["eventyearID"]."'><span class='event'><strong>".$row["event"]."</strong> - ". getEventString($row["type"]) ."</span> <span class='eventleader' data-id='".$row['studentID']."'>$leaderName</span> <a id='leaderlink-".$row["eventyearID"]."' href='#eventyear-leader-".$row["eventyearID"]."'>$leaderStr Leader</a> <a href='javascript:eventYearRemove(\"".$row["eventyearID"]."\")'>Remove</a></div>";
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
		<?=getEventList($mysqlConn, 0,"Events")?>
	</p>
	<p>
		<input class="button fa" type="button" onclick="window.location='#events'" value="&#xf0a8; Return" />
		<input class="submit fa" type="submit" value="&#xf067; Add">
	</p>
</form>
