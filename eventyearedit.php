<?php
require_once  ("php/functions.php");
userCheckPrivilege(5);

$year = getIfSet($_REQUEST['myID'],getCurrentSOYear());
$query = "SELECT * from `eventyear` INNER JOIN `event` ON `eventyear`.`eventID`= `event`.`eventID` WHERE `eventyear`.`year` LIKE '$year' ORDER BY `eventyear`.`divisionID`, `event`.`event`";
echo $query;
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$events = "";
$eventDivision = "";
if($result && mysqli_num_rows($result)>0)
{
while ($row = $result->fetch_assoc()):
	if($eventDivision!=$row['divisionID'])
	{
		if($eventDivision)
		{
			$events .= "</ul>";
		}
		$events .= "<h2>Division ".$row['divisionID']."</h2><ul>";
	}
	$events .= "<li id='eventyear-".$row["eventyearID"]."'><span class='event'><strong>".$row["event"]."</strong> - ". getEventString($row["type"]) ."</span> <button class='btn btn-danger btn-sm' type='button' onclick='eventYearRemove(".$row["eventyearID"].")'><span class='bi bi-trash'></span> Remove</button></li>";
	$eventDivision=$row['divisionID'];
endwhile;
$events .= "</ul>";
}
?>

<form id="addTo" method="post" action="eventyearadd.php">
	<p>
		<label for="year">Year</label>
		<?=getSOYears($year)?>
	</p>
	<div id="eventsP">
		<?=$events?>
	</div>
	<hr>
	<p>
		<?=getDivisionList($mysqlConn, 0,"Division")?>
	</p>
	<p>
		<?=getEventList($mysqlConn, 0,"Events")?>
	</p>
</p>
	<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button>
	<button class='btn btn-primary' type='submit'><span class='bi bi-plus'></span> Add</button>
</p>
</form>
