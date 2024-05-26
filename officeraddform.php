<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);
$schoolID = $_SESSION['userData']['schoolID'];

$year = intval($_POST['myID']);
if(empty($year))
{
	$year = getCurrentSOYear();
}
?>
<form id="addTo" method="post" action="javascript:addToSubmit('officeradd.php')">
	<p>
		<label for="year">Year</label>
		<?=getSOYears($year, 0, $schoolID)?>
	</p>
	<p id="eventsP">
		<label for="student">Student</label>
		<?=getAllStudents(1, NULL)?>
	</p>
	<p>
		<label for="position">Assign Position</label>
		<input id="position" name="position" class="form-control" type="text" placeholder="position" required>
	</p>
		<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button>
			<button class='btn btn-primary' type='submit'><span class='bi bi-plus-circle'></span> Add</button>
	</p>
</form>
