<?php
require_once  ("functions.php");
 ?>
	<?php if($row){ ?>
			<input id="eventID" name="eventID" type="hidden" value="<?=$row["eventID"]?>">
	<?php } ?>
	<p>
		<label for="event">Name</label>
		<input id="event" name="event" type="text" value="<?=$row["event"]?>">
	</p>
	<p>
		<label for="type">Type</label>
		<select id="type" name="type">
			<option value="0" <?=$row["type"]==0||!$row["type"]?"selected":""?>><?=getEventString(0)?></option>
			<option value="1" <?=$row["type"]==1?"selected":""?>><?=getEventString(1)?></option>
			<option value="2" <?=$row["type"]==2?"selected":""?>><?=getEventString(2)?></option>
			<option value="3" <?=$row["type"]==3?"selected":""?>><?=getEventString(3)?></option>
			<option value="4" <?=$row["type"]==4?"selected":""?>><?=getEventString(4)?></option>
		</select>
	</p>
	<p>
		<label for="calculatorType">Calculator</label>
		<select id="calculatorType" name="calculatorType">
			<option value="0" <?=$row["calculatorType"]==0||!$row["calculatorType"]?"selected":""?>><?=getCalulatorString(0)?></option>
			<option value="1" <?=$row["calculatorType"]==1?"selected":""?>><?=getCalulatorString(1)?></option>
			<option value="2" <?=$row["calculatorType"]==2?"selected":""?>><?=getCalulatorString(2)?></option>
			<option value="3" <?=$row["calculatorType"]==3?"selected":""?>><?=getCalulatorString(3)?></option>
		</select>

	</p>
	<p>
		<label for="goggleType">Goggles</label>
		<select id="goggleType" name="goggleType">
			<option value="0" <?=$row["goggleType"]==0||!$row["goggleType"]?"selected":""?>><?=getGoggleString(0)?></option>
			<option value="1" <?=$row["goggleType"]==1?"selected":""?>><?=getGoggleString(1)?></option>
			<option value="2" <?=$row["goggleType"]==2?"selected":""?>><?=getGoggleString(2)?></option>
		</select>
	<p>
		<label for="numberStudents">Number of Partners</label>
		<input id="numberStudents" name="numberStudents" type="number" value="<?=$row["numberStudents"]?$row["numberStudents"]:"2"?>">
	</p>
	<p>
		<label for="sciolyLink">Scioly Link</label>
		<input id="sciolyLink" name="sciolyLink" type="text" value="<?=$row["sciolyLink"]?>">
	</p>
	<p>
		<label for="description">Description</label>
		<input id="description" name="description" type="text" value="<?=$row["description"]?>">
	</p>
