<?php
require_once  ("php/functions.php");
userCheckPrivilege(2);

$myID = intval($_POST['myID']);
$year = getCurrentSOYear();
$schoolID = $_SESSION['userData']['schoolID'];
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['userID']);
$studentIDWhere = "";
if($studentID)
{
	$studentIDWhere ="AND `student`.`studentID` != $studentID";
}

//get all the tournaments that the student has competed in
function getStudentsTournamentList($db, $studentID, $schoolID)
{
	$query = "SELECT `tournament`.`tournamentID`,`teamName`,`tournamentName`,`dateTournament` FROM `student` INNER JOIN `teammate` ON `student`.`studentID`=`teammate`.`studentID` INNER JOIN `team` ON `teammate`.`teamID`=`team`.`teamID` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID`  WHERE `student`.`schoolID`=$schoolID AND `student`.`studentID`=$studentID ORDER BY `dateTournament` DESC, `teamName`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output ="<div id='tournamentDiv'><label for='tournament'>Tournaments</label> ";
	$output .="<select class='form-select' id='tournament' name='tournament' required>";
	if($result && mysqli_num_rows($result)>0)
	{
		while ($row = $result->fetch_assoc()):
			$output .= "<option value='".$row['tournamentID']."'>" . $row['tournamentName'] . " - ". $row['teamName'] ." (" . $row['dateTournament']  .")</option>";
		endwhile;
	}
	$output.="</select></div>";
	return $output;
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
	WHERE `student`.`schoolID`=$schoolID AND `student`.`active` = 1 AND `tournamentevent`.`eventID`= $eventID AND `tournament`.`notCompetition` = 1
	ORDER BY `student`.`last`,`student`.`first`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$output.="<div id='studentAttendance'>";
		while ($row = $result->fetch_assoc())
		{
			//show student name
			$output.="<h3>".$row['first']." ".$row['last']."</h3>";
			//radio buttons for marking attendance
			$output.="<div><label name='attendance-".$row['studentID']."'>Present</label>"; // present label
			$output.="<p><input type='radio' name='attendance-".$row['studentID']."' value='1'></p>";
			$output.="<label name='attendance-".$row['studentID']."'>Absent - Unexcused</label>"; // absent excused label
			$output.="<p><input type='radio' name='attendance-".$row['studentID']."' value='0'></p>";
			$output.="<label name='attendance-".$row['studentID']."'>Absent - Excused</label>"; // absent unexcused label
			$output.="<p><input type='radio' name='attendance-".$row['studentID']."' value='-1' checked='checked'></p>";
			$output.="</div>";
			//sliders for engagement and hw of student during meeting
			$output.="<div><label name='engagement-".$row['studentID']."'>Engagement - 1 for least, 3 for most</label>";
			$output.="<p><input type='range' name='engagement-".$row['studentID']."' min='1' max='3' value='3'></p>";
			$output.="<label name='homework-".$row['studentID']."'>Homework - 1 for incomplete, 3 for fully complete</label>";
			$output.="<p><input type='range' name='homework-".$row['studentID']."' min='1' max='3' value='3'></p></div><hr>"; // line break after each student
		}
		$output .="</div>";
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
	<br>
	<label for="meetingTimeOut">Meeting Time Out</label>
	<input id="meetingTimeOut" name="meetingTimeOut" type="time">

	<br>
	<label for="studentAttendance"></label>
	<?=getEventAttendanceTable($mysqlConn, $schoolID, $eventID)?>

	<label for="student"></label>
	<?=getAllStudents($mysqlConn,1, $row['studentID'])?>
	<button class="btn btn-warning" type="button" onclick="javascript:eventAttendanceAddStudent('<?=$myID?>')"><span class='bi bi-plus-circle'> Add Student</button>
	
	<br>
	<label for="desc">Meeting Description</label>
	<textarea id="desc" name="desc" class="form-control" type="text"></textarea>

	<label for="meetingHW">Meeting Homework</label>
	<textarea id="meetingHW" name="meetingHW" class="form-control" type="text"></textarea>

	<button class='btn btn-primary' type="submit">Submit</button>
</form>
<p>
	<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button>
</p>
<script defer>
	function removeAttribute() {
		var selectElement = document.getElementById('studentID');
		if (selectElement) {
			selectElement.removeAttribute('required');
		}
	}
	window.onload = removeAttribute();

	var form = document.getElementById('addTo');
	form.addEventListener('submit', function() {
		if(confirm('Are you sure you want to submit your meeting attendance?'))
		{
			alert('Meeting attendance submitted!');
			window.history.back();
		}
		else {
			event.preventDefault();
		}
	}, false);
</script>
