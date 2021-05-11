<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
require_once ("functions.php");
?>

<form id="addLeader" method="post" action="eventyearleaderadd.php" class="modal">
<h2>Assign Leader in <span id="year"></span></h2>
	<div>
		<span id="eventID"></span> <span id="eventName"></span>
	</div>
	<div id="students">
		<?=getAllStudents($mysqlConn,1)?>
	</div>
	<br>
	<div>
		<input class="button" type="button" onclick="javascript:prepareEventsYearPage($('#year').html())" value="Cancel" />
		<input class="submit" type="submit" value="Add">
	</div>
</form>
