<?php
require_once  ("php/functions.php");
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
			<option value="0" <?=getSelected($row["type"],0)?>><?=getEventString(0)?></option>
			<option value="1" <?=getSelected($row["type"],1)?>><?=getEventString(1)?></option>
			<option value="2" <?=getSelected($row["type"],2)?>><?=getEventString(2)?></option>
			<option value="3" <?=getSelected($row["type"],3)?>><?=getEventString(3)?></option>
			<option value="4" <?=getSelected($row["type"],4)?>><?=getEventString(4)?></option>
		</select>
	</p>
	<p>
		<label for="weightingDefault">Default Weight</label>
		<input id="weightingDefault" name="weightingDefault" type="number" min='0' max='100' value="<?=isset($row["weightingDefault"])?$row["weightingDefault"]:100?>">
	</p>
	<p>
		<label for="calculatorType">Calculator</label>
		<select id="calculatorType" name="calculatorType">
			<option value="0" <?=getSelected($row["calculatorType"],0)?>><?=getCalulatorString(0)?></option>
			<option value="1" <?=getSelected($row["calculatorType"],1)?>><?=getCalulatorString(1)?></option>
			<option value="2" <?=getSelected($row["calculatorType"],2)?>><?=getCalulatorString(2)?></option>
			<option value="3" <?=getSelected($row["calculatorType"],3)?>><?=getCalulatorString(3)?></option>
		</select>

	</p>
	<p>
		<label for="goggleType">Goggles</label>
		<select id="goggleType" name="goggleType">
			<option value="0" <?=getSelected($row["goggleType"],0)?>><?=getGoggleString(0)?></option>
			<option value="1" <?=getSelected($row["goggleType"],1)?>><?=getGoggleString(1)?></option>
			<option value="2" <?=getSelected($row["goggleType"],2)?>><?=getGoggleString(2)?></option>
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
