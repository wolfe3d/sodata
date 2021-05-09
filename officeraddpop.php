<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");

//check for permissions to add/edit an event
if($_SESSION['userData']['privilege']<4 )
{
	echo "You do not have permissions to add an officer.";
	exit();
}

$year = intval($_POST['year']);
if(empty($year))
{
	$year = getCurrentSOYear();
}
?>
<form id="addTo" method="post" action="officeradd.php">
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
		<input class="button" type="button" onclick="window.location='#officers'" value="Return" />
		<input class="submit" type="submit" value="Add">
	</div>
</form>
