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
                            "load",
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
        loadAttendanceData(<?= $meetingID ?>);
    });
</script>
