<?php
require_once  ("php/functions.php");
userCheckPrivilege(2);
$year = isset($_POST['myID'])?intval($_POST['myID']):getCurrentSOYear();
$studentID = getStudentID($_SESSION['userData']['userID']);
$studentIDWhere = "";
if($studentID)
{
	$studentIDWhere ="AND `student`.`studentID` != $studentID";
}

function getAttendanceTypes()
{
	global $mysqlConn;
	//$myYear = isset($myYear) ? $myYear : getCurrentSOYear();
	$output = "<select class='form-select' id='meetingType' name='meetingType' onchange='showAttendanceTable()' required>";
	$query = "SELECT `meetingtype`.`meetingTypeName`, `meetingtype`.`meetingTypeID` FROM `meetingtype`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed: $query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	// Add default blank option
	$output .= "<option disabled selected value='0'>Select an option</option>";
	while($row = $result->fetch_assoc())
	{
		$name = $row['meetingTypeName'];
		$id = $row['meetingTypeID'];
		// Event Meeting Attendance - Event Leaders
		if($id == 1 && userHasPrivilege(2))
		{
			$output .="<option value='$id' >$name";
			$output .="</option>";
		}
		// ID: 2 | General Meeting Attendance - Secretary and Captain
		// ID: 3 | Officer Meeting Attendance - Secretary and Captain
		// ID: 4 | Event Leader Attendance - Secretary and Captain
		else if(($id == 2 || $id == 3 || $id == 4) && (userHasPrivilege(3)))
		{
			$output .="<option value='$id' >$name";
			$output .="</option>";
		}
	}
	$output .="</select>";
	return $output;
}

function inputAttendance($studentID)
{
	$output = "<p>Attendance: P = Present, AU = Absent Unexcused, AE = Absent Excused (Contacted you with a reason before meeting / Absent from school)</p>
			<div class='form-check form-check-inline'>
				<input class='form-check-input' type='radio' name='attendance-${studentID}' id='attendance-${studentID}-P' value='1' checked>
				<label class='form-check-label' for='attendance-${studentID}-P'>P</label>
			</div>
			<div class='form-check form-check-inline'>
				<input class='form-check-input' type='radio' name='attendance-${studentID}' id='attendance-${studentID}-AU' value='0'>
				<label class='form-check-label' for='attendance-${studentID}-AU'>AU</label>
			</div>
			<div class='form-check form-check-inline'>
				<input class='form-check-input' type='radio' name='attendance-${studentID}' id='attendance-${studentID}-AE' value='-1'>
				<label class='form-check-label' for='attendance-${studentID}-AE'>AE</label>
			</div>";
	return $output;
}
function inputEngagement($studentID)
{
	$output = "<p>Engagement: 0 for not engaged, 1 for partially engaged, 2 for fully participated</p>
			<div class='form-check form-check-inline'>
				<input class='form-check-input' type='radio' name='engagement-${studentID}' id='engagement-${studentID}-0' value='0'>
				<label class='form-check-label' for='engagement-${studentID}-0'>0</label>
			</div>
			<div class='form-check form-check-inline'>
				<input class='form-check-input' type='radio' name='engagement-${studentID}' id='engagement-${studentID}-1' value='1'>
				<label class='form-check-label' for='engagement-${studentID}-1'>1</label>
			</div>
			<div class='form-check form-check-inline'>
				<input class='form-check-input' type='radio' name='engagement-${studentID}' id='engagement-${studentID}-2' value='2' checked>
				<label class='form-check-label' for='engagement-${studentID}-2'>2</label>
			</div>";
	return $output;
}
function inputHomework($studentID)
{
	$output = "<p>Homework: 0 for Not Submitted or No Homework, 1 for partially incomplete, 2 for fully complete</p>
			<div class='form-check form-check-inline'>
				<input class='form-check-input' type='radio' name='homework-${studentID}' id='homework-${studentID}-0' value='0' checked>
				<label class='form-check-label' for='homework-${studentID}-0'>0</label>
			</div>
			<div class='form-check form-check-inline'>
				<input class='form-check-input' type='radio' name='homework-${studentID}'' id='homework-${studentID}-1' value='1'>
				<label class='form-check-label' for='homework-${studentID}-1'>1</label>
			</div>
			<div class='form-check form-check-inline'>
				<input class='form-check-input' type='radio' name='homework-${studentID}' id='homework-${studentID}-2' value='2'>
				<label class='form-check-label' for='homework-${studentID}-2'>2</label>
			</div>";
	return $output;
}


//Repurposed function from eventemails.php - get names of all students on an event and creates attendance table
function getEventAttendanceTable($eventID)
{
	global $mysqlConn, $schoolID;
	$output = "";
	$year = getCurrentSOYear();
	$query = "SELECT DISTINCT `student`.`studentID`, `student`.`last`, `student`.`first`, `student`.`email`, `student`.`emailSchool`, `event`.`event` FROM `tournament` 
	INNER JOIN `tournamentevent` USING (`tournamentID`) 
	INNER JOIN `event` USING (`eventID`) 
	INNER JOIN `teammateplace` USING (`tournamenteventID`) 
	INNER JOIN `student` USING (`studentID`) 
	WHERE `student`.`active` = 1 AND `tournamentevent`.`eventID`= $eventID AND `tournament`.`notCompetition` = 1
	ORDER BY `student`.`last`,`student`.`first`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$output.="<div>";
		while ($row = $result->fetch_assoc())
		{
			//show student name
			$formattedName = $row['first']." ".$row['last'];
			$studentID = $row['studentID'];	
			$output .= "<h3>${formattedName}</h3>";
			$output .= inputAttendance($studentID);
			$output .= inputEngagement($studentID);
			$output .= inputHomework($studentID);
			$output.="<hr>";
		}
	}
	$output .= "</div>";
	return $output;
}
/*
//this function loads a lot of student information in the page and it may not be used.  This decreases page load speed.
function getGeneralAttendanceTable()
{
	global $mysqlConn, $schoolID;
	$output = "";
	$year = getCurrentSOYear();
	$query = "SELECT * FROM `student` 
	WHERE `student`.`active` = 1 AND `schoolID` = ". $_SESSION['userData']['schoolID']. " ORDER BY `last`,`first` ASC";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$output.="<div>";
		while ($row = $result->fetch_assoc())
		{
			//show student name
			$formattedName = $row['first']." ".$row['last'];
			$studentID = $row['studentID'];	
			$output .= "<h3>${formattedName}</h3>";
			$output .= inputAttendance($studentID);
			$output .= "<hr>";
		}
	}
	$output .= "</div>";
	return $output;
}
	*/
/*function getOfficerAttendanceTable()
{
	global $mysqlConn, $schoolID;
	$output = "";
	$year = getCurrentSOYear();
	$query = "SELECT DISTINCT `student`.`studentID`, `student`.`last`, `student`.`first`, `student`.`email`, `student`.`emailSchool`, `officer`.`position`
	FROM `officer` 
	INNER JOIN `student` USING (`studentID`) 
	WHERE `student`.`active` = 1 AND `officer`.`year` = $year
	ORDER BY `officer`.`officerID` ASC";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$output.="<div>";
		while ($row = $result->fetch_assoc())
		{
			//show student name
			$formattedName = $row['first']." ".$row['last']." - ".$row['position'];
			$studentID = $row['studentID'];	
			$output .= "<h3>${formattedName}</h3>";
			$output .= inputAttendance($studentID);
			$output .= "<hr>";
			}
	}
	$output .= "</div>";
	return $output;
}*/
function getEventLeaderAttendanceTable()
{
	global $mysqlConn, $schoolID;
	$output = "";
	$year = getCurrentSOYear();
	$query = "SELECT DISTINCT `student`.`studentID`, `student`.`last`, `student`.`first`, `student`.`email`, `student`.`emailSchool`, `event`.`event`
	FROM `eventleader` 
	INNER JOIN `student` USING (`studentID`) 
	INNER JOIN `event` USING(`eventID`)
	WHERE `student`.`active` = 1 AND `eventleader`.`year` = $year
	ORDER BY `event`.`event` ASC";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$output.="<div>";
		while ($row = $result->fetch_assoc())
		{
			//show student name
			$formattedName = $row['first']." ".$row['last']." - ".$row['event'];
			$studentID = $row['studentID'];	
			$output .= "<h3>${formattedName}</h3>";
			$output .= inputAttendance($studentID);
			$output .= "<hr>";
		}
	}
	$output .= "</div>";
	return $output;
}
function getEventLeadingID($studentID)
{
	global $mysqlConn;
	$year = getCurrentSOYear();
	$query = "SELECT `eventleader`.`eventID` FROM `eventleader` INNER JOIN `student` ON `eventleader`.`studentID` = `student`.`studentID` WHERE `student`.`studentID` = $studentID AND `year` = $year";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['eventID'];
	}
	return "";
}
$eventID = 0;//TODO Fix, see below
$eventList = "";
//TODO: Change getEventLeadingID to output array as student may lead more than one event, make this a choice
if(userHasPrivilege(4))
{
	$eventList= getEventListYear(0,"Event List", $year, null);
}
elseif(userHasPrivilege(2))
{
	$events = getEventLeaderPosition($studentID);
	$eventList = getEventsList($events,0,"Event List",0);
	//$eventID = getEventLeadingID($studentID);
}
$event = 0;//getEventLeaderPosition($studentID)[0]['event'];
//$attendanceTableHTML = json_encode(getEventAttendanceTable($eventID));//TODO  move this to a function into load as teams and all team is now loaded, copy studentcopylist.php and modify it
//$officerAttendanceTableHTML = json_encode(getOfficerAttendanceTable());//moved to use officercopylist.php
//$generalAttendanceTableHTML = json_encode(getGeneralAttendanceTable());
//$eventLeaderAttendanceTableHTML = json_encode(getEventLeaderAttendanceTable());
$date = date('Y-m-d');
$row = NULL; 
?>

<form id="addTo" method="post" action="javascript:addToSubmit('attendanceadd.php')">
<div id="info"></div>
	<label for="meetingType">Meeting Type</label>
	<?=getAttendanceTypes()?>

	<?=$eventList?>

	<label for="meetingDate">Meeting Date</label>
	<br>
	<p value="<?=$date?>" ><u><?=$date?></u></p>
	<input id="meetingDate" name="meetingDate" type="date" value="<?=$date?>" hidden>

	<p>
		<label for="meetingTimeIn">Meeting Time In</label>
		<input id="meetingTimeIn" name="meetingTimeIn" type="time" required>
	</p>
	<p>
		<label for="meetingTimeOut">Meeting Time Out</label>
		<input id="meetingTimeOut" name="meetingTimeOut" type="time" required>
	</p>

	<label for="meetingDesc">Meeting Description</label>
	<textarea id="meetingDesc" name="meetingDesc" class="form-control" type="text"></textarea>

	<label for="meetingHW">Meeting Homework</label>
	<textarea id="meetingHW" name="meetingHW" class="form-control" type="text"></textarea>

	<div id="attendanceContainer"></div>
	<p>
		<div>Select a single student</div>
		<?=getAllStudents(1, $row['studentID'])?>
		<button class="btn btn-warning" type="button" onclick="javascript:attendanceAddStudentSelected()"><span class='bi bi-plus-circle'> Add Student</button>
	</p>

	<p>
		<?=getTeamList(0, "Select All students from a Team")?>
		<div>
		<button class="btn btn-warning" type="button" onclick="javascript:attendanceAddTeam()"><span class='bi bi-plus-circle'> Add Team</button>
		<button class="btn btn-warning" type="button" onclick="javascript:attendanceAddAll()"><span class='bi bi-plus-circle'> Add All Teammates</button>
		</div>
	</p>
	<p>
		<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button>
		<button class='btn btn-primary' type="submit">Submit</button>
	</p>
</form>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
<script defer src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.js"></script>

<script defer>
	
	function loadSummerNoteButtons()
	{
		//The below code causes a bootstrap error, but is necessary for dropdowns in summernote to work.
		let buttons = $('.note-editor button[data-toggle="dropdown"]');
		buttons.each((key, value)=>{
			$(value).attr('data-bs-toggle', 'dropdown');
		})
	}

	function removeAttribute() {
		var selectElement = document.getElementById('studentID');
		if (selectElement) {
			selectElement.removeAttribute('required');
		}
	}
	$(document).ready(function() {
		removeAttribute();
		$('#meetingHW').summernote({focus: true});
		$('#meetingDesc').summernote({focus: true});
		loadSummerNoteButtons();
		showAttendanceTable();
	});

	var form = document.getElementById('addTo');
	form.addEventListener('submit', function() {
		if(confirm('Are you sure you want to submit your meeting attendance?'))
		{
			alert('Meeting attendance submitted!');
			var eventID = document.getElementById("eventsList").value;
			if(document.getElementById('meetingType').value == 1)
			{
				window.location.href = `#event-analysis-${eventID}`;
			} else {
				window.location.href = `#leaders`;
			}
		}
		else {
			event.preventDefault();
		}
	}, false);

	function attendanceAddStudentSelected() {
		var studentID = document.getElementById("studentID").value;
		var selectedName = document.getElementById("studentID").options[document.getElementById("studentID").selectedIndex].text;
		var studentName = selectedName.split(', ');
		if(studentID.length === 0)
		{
			alert("If you would like to add a student, please select a student to add to the event attendance list.");
			return 0;
		}
		//check if the new student was already added before
		if(document.getElementsByName('attendance-'+studentID).length > 0) 
		{
			alert('Student already exists in this meeting!');
			return;
		}
		if(attendanceAddStudent(studentID, studentName[0], studentName[1]))
		{
			$("#info").append("<div class='text-success'>Added "+studentName[0]+" "+studentName[1]+"</div>");
		}
	}

	//Adds an additional student to the meeting attendance page
	function attendanceAddStudent(studentID, last, first) {
		var formattedName = first + ' ' + last;
		if(studentID.length === 0)
		{
			//ignore this student - this may be called as part of adding everyone
			return 0;
		}

		//check if the new student was already added before
		if(document.getElementsByName('attendance-'+studentID).length > 0) 
		{
			//ignore this student - this may be called as part of adding a team
			return 0;
		}
		else
		{
			//create a new div with student information
			//if(confirm("Add student: " + formattedName + "?"))
			//{
				var meetingType = $("#meetingType option:selected").val();
				var newStudent = `<div>
						<h3>${formattedName} </h3>
						<p>Attendance: P = Present, AU = Absent Unexcused, AE = Absent Excused (Contacted you with a reason before meeting / Absent from school)</p>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="attendance-${studentID}" id="attendance-${studentID}-P" value="1" checked>
						<label class="form-check-label" for="attendance-${studentID}-P">P</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="attendance-${studentID}" id="attendance-${studentID}-AU" value="0">
						<label class="form-check-label" for="attendance-${studentID}-AU">AU</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="attendance-${studentID}" id="attendance-${studentID}-AE" value="-1">
						<label class="form-check-label" for="attendance-${studentID}-AE">AE</label>
					</div>`;
					
					if(meetingType == 1)//meetingType 1 = event meeting //TODO change here for adding engagement to other meeting types
					{
						newStudent +=`<p>Engagement: 0 for not engaged, 1 for partially engaged, 2 for fully participated</p>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="engagement-${studentID}" id="engagement-${studentID}-0" value="0">
						<label class="form-check-label" for="engagement-${studentID}-0">0</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="engagement-${studentID}" id="engagement-${studentID}-1" value="1">
						<label class="form-check-label" for="engagement-${studentID}-1">1</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="engagement-${studentID}" id="engagement-${studentID}-2" value="2" checked>
						<label class="form-check-label" for="engagement-${studentID}-2">2</label>
					</div>`;
					}
					if(meetingType == 1)//meetingType 1 = event meeting //TODO change here for adding homework to other meeting types
					{
						newStudent +=`<p>Homework: 0 for Not Submitted or No Homework, 1 for partially incomplete, 2 for fully complete</p>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="homework-${studentID}" id="homework-${studentID}-0" value="0" checked>
						<label class="form-check-label" for="homework-${studentID}-0">0</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="homework-${studentID}" id="homework-${studentID}-1" value="1">
						<label class="form-check-label" for="homework-${studentID}-1">1</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="homework-${studentID}" id="homework-${studentID}-2" value="2">
						<label class="form-check-label" for="homework-${studentID}-2">2</label>
					</div>`;
					}
					newStudent += "<hr>";
				//document.getElementById("studentID").insertAdjacentHTML('beforebegin', newStudent);
				$("#attendanceContainer").append(newStudent);
				return 1;
			//}
		}
	}

	//adds everyone from the team
	function attendanceAddAll()
	{
		$("#studentID > option").each(function() 
		{
			var studentID = this.value;
			var studentName = this.text.split(', ');
			attendanceAddStudent(studentID, studentName[0], studentName[1]);
		}
		);
		$("#info").append("<div class='text-success'>Added all students</div>");
	}


	//Adds a team to the meeting attendance page
	function attendanceAddTeam() {
		//copied most code from function teamCopy(thisTeamID)
		var team = $("#team option:selected").val();
		//get student list on team
		var request = $.ajax({
			url: "teamcopylist.php",
			cache: false,
			method: "POST",
			data: {myID:team},
			dataType: "json"
		});

		request.done(function( data ) {
			$(".text-success").remove(); //removes any old update notices
			$.each( data, function( key, val ) {
				attendanceAddStudent(val["studentID"], val["last"], val["first"]);
			});
			$("#info").append("<div class='text-success'>Added students from "+$("#team option:selected").text()+"</div>");
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#info").append("Add Team Error");
		});
	}

	//Adds a event team to the meeting attendance page
	//TODO: Work on this
	function attendanceAddEvent() {
		//get selected eventID
		var eventID = $("#eventList option:selected").val();
		//get student list on team
		var request = $.ajax({
			url: "eventcopylist.php",
			cache: false,
			method: "POST",
			data: {'eventID':eventID}, //no data sent
			dataType: "json"
		});

		request.done(function( data ) {
			$(".text-success").remove(); //removes any old update notices
			$.each( data, function( key, val ) {
				attendanceAddStudent(val["studentID"], val["last"], val["first"]);
			});
			$("#info").append("<div class='text-success'>Added event member</div>");
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#info").append("Add Event Members Error");
		});
	}

	//Add officers to the meeting attendance page
	function attendanceAddOfficers() {
		var team = $("#team option:selected").val();
		//get student list on team
		var request = $.ajax({
			url: "officercopylist.php",
			cache: false,
			method: "POST",
			data: {}, //no data sent
			dataType: "json"
		});

		request.done(function( data ) {
			$(".text-success").remove(); //removes any old update notices
			$.each( data, function( key, val ) {
				attendanceAddStudent(val["studentID"], val["last"], val["first"]);
			});
			$("#info").append("<div class='text-success'>Added officers</div>");
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#info").append("<div class='text-danger'>Add officers error</div>");
		});
	}

	//Add event leaders to the meeting attendance page
	function attendanceAddEventLeaders() {
		var team = $("#team option:selected").val();
		//get student list on team
		var request = $.ajax({
			url: "eventleadercopylist.php",
			cache: false,
			method: "POST",
			data: {}, //no data sent
			dataType: "json"
		});

		request.done(function( data ) {
			$(".text-success").remove(); //removes any old update notices
			$.each( data, function( key, val ) {
				attendanceAddStudent(val["studentID"], val["last"], val["first"]);
			});
			$("#info").append("<div class='text-success'>Added event leaders</div>");
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#info").append("Add Event Leaders Error");
		});
	}

	function showAttendanceTable() {
		//var officerAttendanceTable = 
		var meetingType = document.getElementById('meetingType').value;
		if(meetingType == 1)
		{
			// show event attendance table with all students in event
			document.getElementById('attendanceContainer').innerHTML = eventAttendanceTable;
			document.getElementById('eventsListDiv').hidden = false;
			$("#eventsListDiv").val($("#eventsListDiv option:first").val());
			attendanceAddEvent();
		}
		else if(meetingType == 2) // General Meeting
		{
			document.getElementById('attendanceContainer').innerHTML = "";//generalAttendanceTable;
			document.getElementById('eventsListDiv').hidden = true;
			$("#eventsListDiv").val("");
		}
		else if(meetingType == 3) // Officer Meeting
		{
			document.getElementById('attendanceContainer').innerHTML = "";//officerAttendanceTable;
			document.getElementById('eventsListDiv').hidden = true;
			$("#eventsListDiv").val("");
			attendanceAddOfficers();
		}
		else if(meetingType == 4) // Event Leader Meeting
		{
			document.getElementById('attendanceContainer').innerHTML = eventLeaderAttendanceTable;
			document.getElementById('eventsListDiv').hidden = true;
			$("#eventsListDiv").val("");
		}
		else
		{
			document.getElementById('attendanceContainer').innerHTML = "";
			document.getElementById('eventsListDiv').hidden = true;
			$("#eventsListDiv").val("");
		}
	}
</script>