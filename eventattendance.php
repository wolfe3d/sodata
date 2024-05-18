<?php
require_once  ("php/functions.php");
userCheckPrivilege(2);

$year = getCurrentSOYear();
$schoolID = $_SESSION['userData']['schoolID'];
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['userID']);
$studentIDWhere = "";
if($studentID)
{
	$studentIDWhere ="AND `student`.`studentID` != $studentID";
}

function getAttendanceTypes($db)
{
	//$myYear = isset($myYear) ? $myYear : getCurrentSOYear();
	$output = "<select class='form-select' id='meetingType' name='meetingType' required>";
	$query = "SELECT `meetingtype`.`meetingTypeName`, `meetingtype`.`meetingTypeID` FROM `meetingtype`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed: $query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	while($row = $result->fetch_assoc())
	{
		$name = $row['meetingTypeName'];
		$id = $row['meetingTypeID'];
		$output .="<option value='$id' >$name</option>";
	}
	$output .="</select>";
	return $output;
}

//Repurposed function from eventemails.php - get names of all students on an event and creates attendance table
function getEventAttendanceTable($db, $schoolID, $eventID)
{
	$output = "";
	$year = getCurrentSOYear();
	$query = "SELECT DISTINCT `student`.`studentID`, `student`.`last`, `student`.`first`, `student`.`email`, `student`.`emailSchool`, `event`.`event` FROM `tournament` 
	INNER JOIN `tournamentevent` USING (`tournamentID`) 
	INNER JOIN `event` USING (`eventID`) 
	INNER JOIN `teammateplace` USING (`tournamenteventID`) 
	INNER JOIN `student` USING (`studentID`) 
	WHERE `student`.`schoolID`=$schoolID AND `student`.`active` = 1 AND `tournamentevent`.`eventID`= $eventID AND `tournament`.`notCompetition` = 1";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$output .="<table class='table table-hover'>";
		$output .="<thead><tr><th>Student</th><th>Present</th><th>Absent - Excused</th><th>Absent - Unexcused</th><th>Engagement - 1 for least, 3 for most</th><th>Homework - 1 for incomplete, 3 for fully complete</th></tr></thead>";
		while ($row = $result->fetch_assoc())
		{
			$output.="<tr><td>".$row['first']." ".$row['last']."</td>";
			$output.="<td><input type='radio' name='attendance-".$row['studentID']."' value='1'></td>";
			$output.="<td><input type='radio' name='attendance-".$row['studentID']."' value='0'></td>";
			$output.="<td><input type='radio' name='attendance-".$row['studentID']."' value='-1' checked='checked'></td>";
			$output.="<td><input type='range' name='engagement-".$row['studentID']."' min='1' max='3' value='3'></td>";
			$output.="<td><input type='range' name='homework-".$row['studentID']."' min='1' max='3' value='3'></td></tr>";
		}
		$output .="</table>";
	}
	return $output;
}
function getEventLeadingID($db, $studentID)
{
	$year = getCurrentSOYear();
	$query = "SELECT `eventleader`.`eventID` FROM `eventleader` INNER JOIN `student` ON `eventleader`.`studentID` = `student`.`studentID` WHERE `student`.`studentID` = $studentID AND `year` = $year";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
	}
	return $row['eventID'];
}
$eventID = getEventLeadingID($mysqlConn, $studentID);
$event = getEventLeaderPosition($mysqlConn, $studentID);
$date = date('Y-m-d');
$row = NULL; 
$action = "javascript:addToSubmit('eventattendanceadd.php')";
?>

<form id="addTo" method="post" action="<?=$action?>">

	<label for="meetingType">Meeting Type</label>
	<?=getAttendanceTypes($mysqlConn)?>

	<label for="meetingName">Event Name</label>
	<br>
	<p value="<?=$eventID?>" ><u><?=$event?></u></p>
	<input id="meetingName" name="meetingName" class="form-control" type="text" value="<?=$eventID?>" hidden>

	<label for="meetingDate">Meeting Date</label>
	<br>
	<p value="<?=$date?>" ><u><?=$date?></u></p>
	<input id="meetingDate" name="meetingDate" type="date" value="<?=$date?>" hidden>

	<label for="meetingTimeIn">Meeting Time In</label>
	<input id="meetingTimeIn" name="meetingTimeIn" type="time">

	<label for="meetingTimeOut">Meeting Time Out</label>
	<input id="meetingTimeOut" name="meetingTimeOut" type="time">

	<label for="student"></label>
	<?=getEventAttendanceTable($mysqlConn, $schoolID, $eventID)?>

	<label for="desc">Meeting Description</label>
	<textarea id="desc" name="desc" class="form-control" type="text"></textarea>

	<label for="meetingHW">Meeting Homework</label>
	<textarea id="meetingHW" name="meetingHW" class="form-control" type="text"></textarea>

	<button class='btn btn-primary' type="submit">Submit</button>
</form>
<p>
	<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button>
</p>

