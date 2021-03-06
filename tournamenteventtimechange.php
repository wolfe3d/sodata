<?php
require_once  ("php/functions.php");
userCheckPrivilege(3);

$output = "";
$myqueryID = intval($_POST['myID']);
if(empty($myqueryID))
{
	echo "<div style='color:red'>timeblockID is not set.</div>";
	exit();
}

//Get timeblock row information
$query = "SELECT `timeStart`,`timeEnd`,`tournamentName`  FROM `timeblock` INNER JOIN `tournament` ON `timeblock`.`tournamentID`= `tournament`.`tournamentID`  WHERE `timeblockID` = $myqueryID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$row = $result->fetch_assoc();

$timeString = date("Y:m:d g:i A",strtotime($row['timeStart'])) ." - " . date("Y:m:d g:i A",strtotime($row['timeEnd']));
echo $output;
?>
<br>
<div id='myTitle'>View Time Block</div>
		<p><?=$timeString?> at <?=$row['tournamentName']?></p>

<?php if(userHasPrivilege(3)){?>
	<form id="addTo" method="post" action="tournamentUpdate.php">
			<label for="note">Change time</label>
			<p>Be careful, this will change the time for all teams using this time block.</p>
			<input id="timeStart" name="timeStart" type="datetime-local" value="<?=date("Y-m-d\TH:i:s", strtotime($row['timeStart']))?>">
			<input id="timeEnd" name="timeEnd" type="datetime-local" value="<?=date("Y-m-d\TH:i:s", strtotime($row['timeEnd']))?>">
	</form>
<?php } else {?>
		<p><?=$timeString?></p>
<?php } ?>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>
