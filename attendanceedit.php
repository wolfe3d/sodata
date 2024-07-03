<?php
require_once  ("php/functions.php");
userCheckPrivilege(2);

$meetingID = intval($_POST['myID']);
if(isset($meetingID))
{
	$query = "SELECT * FROM `meeting` WHERE `meetingID` = $meetingID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row = $result->fetch_assoc();
	$eventID = $row['eventID'];
    $meetingTypeID = $row['meetingTypeID'];

}
function loadAttendanceData($meetingID)
{
    global $mysqlConn;
    $attendanceQuery = "SELECT * FROM `meetingattendance` INNER JOIN `student` ON `student`.`studentID` = `meetingattendance`.`studentID` WHERE `meetingattendance`.`meetingID` = $meetingID";
    $result = $mysqlConn->query($attendanceQuery) or error_log("\n<br />Warning: query failed:$attendanceQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    $studentOutput = "";
    while($attendanceRow = $result -> fetch_assoc()):
        $formattedName = $attendanceRow['first'] . " " . $attendanceRow['last'];
        $studentID = $attendanceRow['studentID'];
        $studentOutput .= "<div>
				<h3>${formattedName}</h3>
				<p>Attendance: P = Present, AU = Absent Unexcused, AE = Absent Excused (Contacted you with a reason before meeting / Absent from school)</p>
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
			</div>
			</div><hr>";
    endwhile;
    return $studentOutput;
}

?>
<form id="addTo" method="post">
	<fieldset>
		<legend>Edit Meeting</legend>
		<?php
        if($meetingTypeID == 1){ ?>
                <input id="eventID" name="eventID" class="form-control" type="hidden" value="<?=$row["eventID"]?>">
        <?php }
        else {
            //$row = NULL;
        }
        ?>
        <p>
            <label for="meetingDate">Meeting Date</label>
            <input id="meetingDate" name="meetingDate" class="form-control" type="date" value="<?=$row["meetingDate"]?>" required>
        </p>
        <p>
            <label for="meetingTimeIn">Time In</label>
            <input id="meetingTimeIn" name="meetingTimeIn" class="form-control" type="time" value="<?=$row["meetingTimeIn"]?>"required>
        </p>
        <p>
            <label for="meetingTimeOut">Time Out</label>
            <input id="meetingTimeOut" name="meetingTimeOut" class="form-control" type="time" value="<?=$row["meetingTimeOut"]?>" required>
        </p>
        <p>
            <label for="meetingDescription">Description</label>
            <input id="meetingDescription" name="meetingDescription" class="form-control" type="text" value="<?=$row["meetingDescription"]?>" required>
        </p>
        <p>
            <label for="meetingHW">Homework</label>
            <input id="meetingHW" name="meetingHW" class="form-control" type="text" value="<?=$row["meetingHW"]?>" required>
        </p>
        <p>
        <div id="attendanceContainer"></div>
        
        </p>

	<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>
	</fieldset>
</form>
<script defer>
    // Function to load attendance data for a specific meeting
    function loadAttendanceData(meetingID) {
        $.ajax({
            url: "attendanceload.php",
            type: "POST",
            data: { 'meetingID': meetingID },
            dataType: "json",
            success: function(data) {
                $("#attendanceContainer").empty();

                if (data && data.length > 0) {
                    data.forEach(student => {
                        attendanceAddStudent(
                            student.studentID,
                            student.last,
                            student.first,
                            "",
                            student.attendance,
                            student.engagement,
                            student.homework
                        );
                    });
                } else {
                    $("#info").append("<div class='text-warning'>No attendance data found for this meeting.</div>");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error loading attendance data: ", textStatus, errorThrown);
                $("#info").append("<div class='text-danger'>Error loading attendance data.</div>");
            }
        });
    }
    $(document).ready(function() {
        var meetingID = <?= $meetingID ?>; // Get the meeting ID dynamically
        loadAttendanceData(meetingID);


    });
    // copied from attendance.php - TODO add to data.js
	//Add a student to the meeting attendance page
	function attendanceAddStudent(studentID, last, first, info, attendance=1, engagement=2, homework=0) {
		var formattedName = first + ' ' + last;
		formattedName += info!=null?" - " + info:"";
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
						<input class="form-check-input" type="radio" name="attendance-${studentID}" id="attendance-${studentID}-P" value="1" ${attendance==1?'checked':''}>
						<label class="form-check-label" for="attendance-${studentID}-P">P</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="attendance-${studentID}" id="attendance-${studentID}-AU" value="-1" ${attendance==-1?'checked':''}>
						<label class="form-check-label" for="attendance-${studentID}-AU">AU</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="attendance-${studentID}" id="attendance-${studentID}-AE" value="0" ${attendance==0?'checked':''}>
						<label class="form-check-label" for="attendance-${studentID}-AE">AE</label>
					</div>`;
					
					if(meetingType == 1)//meetingType 1 = event meeting //TODO change here for adding engagement to other meeting types
					{
						newStudent +=`<p>Engagement: 0 for not engaged, 1 for partially engaged, 2 for fully participated</p>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="engagement-${studentID}" id="engagement-${studentID}-0" value="0" ${engagement==0?'checked':''}>
						<label class="form-check-label" for="engagement-${studentID}-0">0</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="engagement-${studentID}" id="engagement-${studentID}-1" value="1" ${engagement==1?'checked':''}>
						<label class="form-check-label" for="engagement-${studentID}-1">1</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="engagement-${studentID}" id="engagement-${studentID}-2" value="2" ${engagement==2?'checked':''}>
						<label class="form-check-label" for="engagement-${studentID}-2">2</label>
					</div>`;
					}
					if(meetingType == 1)//meetingType 1 = event meeting //TODO change here for adding homework to other meeting types
					{
						newStudent +=`<p>Homework: 0 for Not Submitted or No Homework, 1 for partially incomplete, 2 for fully complete</p>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="homework-${studentID}" id="homework-${studentID}-0" value="0" ${homework==0?'checked':''}>
						<label class="form-check-label" for="homework-${studentID}-0">0</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="homework-${studentID}" id="homework-${studentID}-1" value="1" ${homework==1?'checked':''}>
						<label class="form-check-label" for="homework-${studentID}-1">1</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="homework-${studentID}" id="homework-${studentID}-2" value="2" ${homework==2?'checked':''}>
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


</script>
