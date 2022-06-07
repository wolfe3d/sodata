<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);

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
		<?=getAllStudents($mysqlConn,1, NULL)?>
	</p>
	<p>
		<?=getEventListYear($mysqlConn, 0,'Choose Event', $year)?>
	</p>
		<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='fa fa-arrow-circle-left'></span> Return</button>
		<button class='btn btn-primary' type='submit'><span class='fa fa-plus'></span> Add</button>
	</p>
</form>
