<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
require_once  ("functions.php");

//TODO: BUG FIX the submission

$eventID = intval($_POST['myID']);
$typeName = "";
if(isset($eventID))
{
	$query = "SELECT * FROM `event` WHERE `eventID` = $eventID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row = $result->fetch_assoc();
	$typeName = $row["type"];
}
?>
<form id="addTo" method="post" action="eventeditadjust.php">
	<?php if($row){ ?>
			<input id="eventID" name="eventID" type="hidden" value="<?=$row["eventID"]?>">
	<?php } ?>
	<p>
		<label for="event">Event</label>
		<input id="event" name="event" type="text" value="<?=$row["event"]?>" onchange="fieldUpdate(<?=$eventID?>,'event',this.id,this.value)">
	</p>
	<p>
		<label for="type">Event Type</label>
		<select id="type" name="type" onchange="fieldUpdate(<?=$eventID?>,'event',this.id,this.value)">
			<option value="0" <?=$row["type"]==0||!$row["type"]?"selected":""?>><?=getEventString(0)?></option>
			<option value="1" <?=$row["type"]==1?"selected":""?>><?=getEventString(1)?></option>
			<option value="2" <?=$row["type"]==2?"selected":""?>><?=getEventString(2)?></option>
			<option value="3" <?=$row["type"]==3?"selected":""?>><?=getEventString(3)?></option>
			<option value="4" <?=$row["type"]==4?"selected":""?>><?=getEventString(4)?></option>
		</select>
	</p>
	<p>
		<label for="calculatorType">Calculator</label>
		<select id="calculatorType" name="calculatorType" onchange="fieldUpdate(<?=$eventID?>,'event',this.id,this.value)">
			<option value="0" <?=$row["calculatorType"]==0||!$row["calculatorType"]?"selected":""?>><?=getCalulatorString(0)?></option>
			<option value="1" <?=$row["calculatorType"]==1?"selected":""?>><?=getCalulatorString(1)?></option>
			<option value="2" <?=$row["calculatorType"]==2?"selected":""?>><?=getCalulatorString(2)?></option>
			<option value="3" <?=$row["calculatorType"]==3?"selected":""?>><?=getCalulatorString(3)?></option>
		</select>

	</p>
	<p>
		<label for="goggleType">Goggles</label>
		<select id="goggleType" name="goggleType" onchange="fieldUpdate(<?=$eventID?>,'event',this.id,this.value)">
			<option value="0" <?=$row["goggleType"]==0||!$row["goggleType"]?"selected":""?>><?=getGoggleString(0)?></option>
			<option value="1" <?=$row["goggleType"]==1?"selected":""?>><?=getGoggleString(1)?></option>
			<option value="2" <?=$row["goggleType"]==2?"selected":""?>><?=getGoggleString(2)?></option>
		</select>
	<p>
		<label for="numberStudents">Number of Partners</label>
		<input id="numberStudents" name="numberStudents" type="number" value="<?=$row["numberStudents"]?>" onchange="fieldUpdate(<?=$eventID?>,'event',this.id,this.value)">
	</p>
	<p>
		<label for="sciolyLink">Scioly Link</label>
		<input id="sciolyLink" name="sciolyLink" type="text" value="<?=$row["sciolyLink"]?>" onchange="fieldUpdate(<?=$eventID?>,'event',this.id,this.value)">
	</p>
	<p>
		<label for="description">Description</label>
		<input id="description" name="description" type="text" value="<?=$row["description"]?>" onchange="fieldUpdate(<?=$eventID?>,'event',this.id,this.value)">
	</p>
	<p>
		<input class="button fa" type="button" onclick="window.history.back()" value="&#xf0a8; Return" />
	</p>
</form>
