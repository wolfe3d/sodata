<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
require_once ("functions.php");

$eventyearID = intval($_POST['myID']);
if(empty($eventyearID))
{
	exit("Event year was not sent!");
}
$query .= "SELECT * from `eventyear` INNER JOIN `event` ON `eventyear`.`eventID`= `event`.`eventID` LEFT JOIN `student` ON `eventyear`.`studentID`= `student`.`studentID` WHERE `eventyear`.`eventyearID` LIKE '$eventyearID' ORDER BY `event`.`event` ASC ";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if($result){
	$row = $result->fetch_assoc();
	if($row['studentID'])
	{
		$prefix = "Edit";
	}
	else {
		$prefix = "Assign";
	}
}
?>

<form id="addLeader" method="post" action="eventyearleaderadd.php" class="modal">
<h2><?=$prefix?> Leader to <span id="eventName"><?=$row['event']?> in <span id="year"><?=$row['year']?></span></h2>
	<div id="students">
		<?=getAllStudents($mysqlConn,1,$row['studentID'])?>
	</div>
	<br>
	<div>
		<input class="button fa" type="button" onclick="window.location='#eventyear-edit-<?=$row['year']?>'" value="&#xf0a8; Return" />
	</div>
</form>