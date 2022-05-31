<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
require_once  ("functions.php");

$output = "";
$tournamenteventID = intval($_POST['myID']);
if(empty($tournamenteventID))
{
	echo "<div style='color:red'>tournamenteventID is not set.</div>";
	exit();
}

//Get tournamentevent row information
$query = "SELECT `tournamentevent`.`note`,`tournamentName`,`event`  FROM `tournamentevent` INNER JOIN `tournament` ON `tournamentevent`.`tournamentID`= `tournament`.`tournamentID` INNER JOIN `event` ON `tournamentevent`.`eventID` = `event`.`eventID` WHERE `tournamenteventID` = $tournamenteventID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$row = $result->fetch_assoc();

echo $output;
?>
<br>
<div id='myTitle'>View Event Note</div>
		<p><?=$row['event']?> at <?=$row['tournamentName']?></p>

<?php if(userHasPrivilege(3)){?>
	<form id="addTo" method="post" action="tournamentUpdate.php">
			<label for="note">Event Note</label>
			<input id="note" name="note" type="text" value="<?=$row['note']?>">
	</form>
<?php } else {?>
		<p><?=$row['note']?></p>
<?php } ?>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()'><span class='fa fa-arrow-circle-left'></span> Return</button></p>
