<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(4);
require_once  ("functions.php");

$year = intval($_POST['myID']);
if(empty($year))
{
	$year = getCurrentSOYear();
}
?>
<form id="addTo" method="post" action="officeraddadjust.php">
	<p>
		<label for="year">Year</label>
		<?=getSOYears($year)?>
	</p>
	<p id="eventsP">
		<label for="student">Student</label>
		<?=getAllStudents($mysqlConn,1)?>
	</p>
	<p>
		<label for="position">Assign Position</label>
		<input id="position" name="position" type="text" value="position" onchange="officerAdd()">
	</p>
		<input class="button" type="button" onclick="window.history.back()" value="Cancel" />
		<input class="submit" type="submit" value="Add">
	</div>
</form>
