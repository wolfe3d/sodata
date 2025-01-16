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

//TODO: Add function to edit old meetings, load similar way to the teamcopylist.php etc...
//Must add loading of meeting notes, and meeting homework, attendance, engagement, homework for each student
//put function in data.js that first loads the meetingID, homework and meeting notes, then loads students 
//and finally loads this page and puts in all the students

function getAttendanceTypes()
{
	global $mysqlConn;
	//$myYear = isset($myYear) ? $myYear : getCurrentSOYear();
	$output = "<select class='form-select' id='meetingType' name='meetingType' onchange='showAttendanceTable()' required>";
	$query = "SELECT `meetingtype`.`meetingTypeName`, `meetingtype`.`meetingTypeID` FROM `meetingtype`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed: $query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	// Add default blank option
	//$output .= "<option disabled selected value='0'>Select an option</option>";//not necessary
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

$eventList = "";
if(userHasPrivilege(4))
{
	//show all events for this year for high privilege
	$eventList= getEventListYear(0,"Event List", $year, null);
}
elseif(userHasPrivilege(2))
{
	//show only leading events for officer and event leader
	$events = getEventLeaderPosition($studentID);
	$eventList = getEventsList($events,0,"Event List",0);
}
$event = 0;//getEventLeaderPosition($studentID)[0]['event'];

$row = NULL;
$date = date('Y-m-d');
?>
<form id="addTo" method="post" action="javascript:attendanceSubmit('attendanceadd.php')">
<div id="info"></div>
	<label for="meetingType">Meeting Type</label>
	<?=getAttendanceTypes()?>

	<?=$eventList?>

	<label for="meetingDate">Meeting Date</label>
	<br>
	<p id="displayDate"></p>
	<input id="meetingDate" name="meetingDate" type="date" value="<?=$date?>" hidden>

	<p>
		<label for="meetingTimeIn">Meeting Time In</label>
		<input id="meetingTimeIn" name="meetingTimeIn" type="time" required>
	</p>
	<p>
		<label for="meetingTimeOut">Meeting Time Out</label>
		<input id="meetingTimeOut" name="meetingTimeOut" type="time" required>
	</p>
	<p>
		<label for="meetingDescription">Meeting Description</label>
		<textarea id="meetingDescription" name="meetingDescription" class="form-control"></textarea>
	</p>
	<p>
		<label for="meetingHW">Meeting Homework</label>
	<textarea id="meetingHW" name="meetingHW" class="form-control"></textarea>
	</p>
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

<script defer>	
	function displayTime() {
		var localDate = new Date(<?=time() * 1000?>);
		var formattedDate = `${localDate.getFullYear()}-${String(localDate.getMonth() + 1).padStart(2, '0')}-${String(localDate.getDate()).padStart(2, '0')}`;
		document.getElementById("displayDate").innerHTML = formattedDate;
		document.getElementById('meetingDate').value = formattedDate;
	}

	function removeAttribute() {
		var selectElement = document.getElementById('studentID');
		if (selectElement) {
			selectElement.removeAttribute('required');
		}
	}
	$(document).ready(function() {
		displayTime();
		removeAttribute();
		$('#meetingHW').summernote({focus: true});
		$('#meetingDescription').summernote({focus: true});
		showAttendanceTable();
		$("#eventsList").on( "change", function (e) {
			attendanceAddEvent();
		});
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
		if(document.getElementsByName('attendanceStudent-'+studentID).length > 0) 
		{
			alert('Student already exists in this meeting!');
			return;
		}
		if(attendanceAddStudent("",studentID, studentName[0], studentName[1]),"")
		{
			$("#info").append("<div class='text-success'>Added "+studentName[0]+" "+studentName[1]+"</div>");
		}
	}

	//adds everyone from the team
	function attendanceAddAll()
	{
		$("#studentID > option").each(function() 
		{
			var studentID = this.value;
			var studentName = this.text.split(', ');
			attendanceAddStudent("",studentID, studentName[0], studentName[1], "");
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
			data: {'team':team},
			dataType: "json"
		});

		request.done(function( data ) {
			$(".text-success").remove(); //removes any old update notices
			$.each( data, function( key, val ) {
				attendanceAddStudent("",val["studentID"], val["last"], val["first"],"");
			});
			$("#info").append("<div class='text-success'>Added students from "+$("#team option:selected").text()+"</div>");
		});

		request.fail(function( jqXHR, textStatus ) {
			$("#info").append("Add Team Error");
		});
	}

	//Adds a event team to the meeting attendance page
	function attendanceAddEvent() {
		//get selected eventID
		var eventID = $("#eventsList option:selected").val();
		//clear attendance table
		document.getElementById('attendanceContainer').innerHTML = "";
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
				attendanceAddStudent("",val["studentID"], val["last"], val["first"],"");
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
				attendanceAddStudent("",val["studentID"], val["last"], val["first"], val["position"]);
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
				attendanceAddStudent("",val["studentID"], val["last"], val["first"], val["event"]);
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
			document.getElementById('attendanceContainer').innerHTML = "";
			document.getElementById('eventsListDiv').hidden = true;
			$("#eventsListDiv").val("");
			attendanceAddEventLeaders();
		}
		else
		{
			document.getElementById('attendanceContainer').innerHTML = "";
			document.getElementById('eventsListDiv').hidden = true;
			$("#eventsListDiv").val("");
		}
	}
</script>
