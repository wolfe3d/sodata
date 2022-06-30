<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);

$year = intval($_POST['myID']);
$eventID = intval($_POST['event']);
if(empty($year))
{
	$year = getCurrentSOYear();
}
?>
<form id="addTo" method="post" action="javascript:addToSubmit('eventleaderadd.php')">
	<p>
		<label for="year">Year</label>
		<?=getSOYears($year)?>
	</p>
	<p id="eventsP">
		<label for="student">Student</label>
		<?=getAllStudents($mysqlConn,1, NULL)?>
	</p>
	<p>
		<?=getEventListYear($mysqlConn, 0,'Choose Event', $year, $eventID)?>
	</p>
		<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button>
		<button class='btn btn-primary' type='submit'><span class='bi bi-plus-circle'></span> Add</button>
	</p>
</form>
