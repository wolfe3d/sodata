<?php
require_once  ("../connectsodb.php");
//text output
$output = "";

/*check to see if id exists*/
$query = "SELECT * from `phonetype`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$phoneTypes="";
if($result)
{
	while ($row = $result->fetch_assoc()):
		$phoneTypes.="<option value = '".$row['phoneType']."'>".$row['phoneType']."</option>";
	endwhile;
}

$studentID = intval($_REQUEST['studentID']);

$query = "SELECT * from `students` WHERE `studentID`=$studentID ";// where `field` = $fieldId";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$row = $result->fetch_assoc();
}

//find student's events
$query = "SELECT * FROM `eventschoice` t1 INNER JOIN `eventsyear` t2 ON t1.`eventID`=t2.`eventID` WHERE `studentID`=$studentID ORDER BY t2.`year` DESC, t1.`priority` ASC";// where `field` = $fieldId";
$resultEventsChoice = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$eventsChoice ="";
if(mysqli_num_rows($resultEventsChoice)>0)
{
	$eventsChoice .="<div>Year-Priority Event Name</div>";
	while ($rowEventsChoice = $resultEventsChoice->fetch_assoc()):
		$eventsChoice .= "<div id='eventChoice-" . $rowEventsChoice['eventsChoiceID'] . "'>" . $rowEventsChoice['year'] . "-" . $rowEventsChoice['priority'] . " " . $rowEventsChoice['event'] . " <a href='' onclick=\"removeEvent('" . $rowEventsChoice['eventsChoiceID'] . "');return false;\">Remove</a></div>";
	endwhile;
}


//find student's courses completed
$query = "SELECT * FROM `coursescompleted` t1 INNER JOIN `courses` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=$studentID ORDER BY t2.`course` ASC";// where `field` = $fieldId";
$resultCourses = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$coursesCompleted ="";
if(mysqli_num_rows($resultCourses)>0)
{
	$coursesCompleted .="<div>Course Name - Level</div>";
	while ($rowCourse = $resultCourses->fetch_assoc()):
		$coursesCompleted .= "<div id='coursesCompleted-" . $rowCourse['myID'] . "'>" . $rowCourse['course'] . " - " . $rowCourse['level'] . " <a href='' onclick=\"removeCourse('" . $rowCourse['myID'] . "','coursesCompleted');return false;\">Remove</a></div>";
	endwhile;
}

//find student's courses enrolled but not yet completed
$query = "SELECT * FROM `coursesenrolled` t1 INNER JOIN `courses` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=$studentID ORDER BY t2.`course` ASC";// where `field` = $fieldId";
$resultCourses = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$coursesEnrolled ="";
if(mysqli_num_rows($resultCourses)>0)
{
	$coursesEnrolled .="<div>Course Name - Level</div>";
	while ($rowCourse = $resultCourses->fetch_assoc()):
		$coursesEnrolled .= "<div id='coursesEnrolled-" . $rowCourse['myID'] . "'>" . $rowCourse['course'] . " - " . $rowCourse['level'] . " <a href='' onclick=\"removeCourse('" . $rowCourse['myID'] . "','coursesEnrolled');return false;\">Remove</a> <a href='' onclick=\"moveCourse('" . $rowCourse['myID'] . "');return false;\">Completed</a></div>";
	endwhile;
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Pragma" content="no-cache">
	<script src="../lib/jquery.js"></script>
	<script src="../lib/jquery.validate.min.js"></script>
  <script src="studentedit.js"></script>
	</head>
	<body>
<form id="studentUpdate" method="post" action="studentUpdate.php">
		<fieldset>
			<legend>Edit Student</legend>
			<p>
				<label for="first">Firstname</label>
				<input id="first" name="first" type="text" value="<?=$row['first']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
			</p>
			<p>
				<label for="last">Lastname</label>
				<input id="last" name="last" type="text" value="<?=$row['last']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
			</p>
			<p>
				<label for="yearGraduating">Year Graduating</label>
				<input id="yearGraduating" name="yearGraduating" type="text" value="<?=$row['yearGraduating']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
			</p>
			<p>
				<label for="email">Email</label>
				<input id="email" name="email" type="email" value="<?=$row['email']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
			</p>
			<p>
				<label for="emailAlt">Alternate Email</label>
				<input id="emailAlt" name="emailAlt" type="email" value="<?=$row['emailAlt']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
			</p>
			<p>
				<label for="phoneType">Phone Type</label>
				<select id="phoneType" name="text" value="<?=$row['phoneType']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
					<?=$phoneTypes?>
				</select>
			</p>
			<p>
				<label for="phone">Phone</label>
				<input id="phone" name="phone" type="tel" value="<?=$row['phone']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
			</p>
			<fieldset>
				<legend>Events</legend>
				<div id="events"><?=$eventsChoice?></div>
				<div id="addEventsDiv">
			</div>
				<a id="addEvent" onclick="addEventChoice(<?=$studentID?>);$(this).hide();return false;" href="">Add Event</a>
			</fieldset>
			<fieldset>
				<legend>Courses - Completed</legend>
				<div id="coursesCompleted"><?=$coursesCompleted?></div>
				<div id="addcoursesCompletedDiv">
			</div>
				<a id="addCoursesCompleted" class="addCoursesBtn" onclick="addCoursesChoice('<?=$studentID?>','coursesCompleted');$(this).hide();return false;" href="">Add Course Completed</a>
			</fieldset>
			<fieldset>
				<legend>Courses - Enrolled (but not completed)</legend>
				<div id="coursesEnrolled"><?=$coursesEnrolled?></div>
				<div id="addcoursesEnrolledDiv">
			</div>
				<a id="addcoursesEnrolled" class="addCoursesBtn" onclick="addCoursesChoice('<?=$studentID?>','coursesEnrolled');$(this).hide();return false;" href="">Add Course Enrolled</a>
			</fieldset>
			<fieldset>
				<legend>Parent 1</legend>
				<p>
					<label for="parent1First">First</label>
					<input id="parent1First" name="parent1First" type="text" value="<?=$row['parent1First']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
				</p>
				<p>
					<label for="parent1Last">Last</label>
					<input id="parent1Last" name="parent1Last" type="text" value="<?=$row['parent1Last']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
				</p>
				<p>
					<label for="parent1Email">Email</label>
					<input id="parent1Email" name="parent1Email" type="email" value="<?=$row['parent1Email']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
				</p>
				<p>
					<label for="parent1Phone">Phone</label>
					<input id="parent1Phone" name="parent1Phone" type="tel" value="<?=$row['parent1Phone']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
				</p>
			</fieldset>
			<fieldset>
				<legend>Parent 2</legend>
				<p>
					<label for="parent2First">First</label>
					<input id="parent2First" name="parent2First" type="text" value="<?=$row['parent2First']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
				</p>
				<p>
					<label for="parent2Last">Last</label>
					<input id="parent2Last" name="parent2Last" type="text" value="<?=$row['parent2Last']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
				</p>
				<p>
					<label for="parent2Email">Email</label>
					<input id="parent2Email" name="parent2Email" type="email" value="<?=$row['parent2Email']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
				</p>
				<p>
					<label for="parent2Phone">Phone</label>
					<input id="parent2Phone" name="parent2Phone" type="tel" value="<?=$row['parent2Phone']?>" onchange="updateStudent(<?=$studentID?>,this.id,this.value)">
				</p>
			</fieldset>
		</fieldset>
	</form>
	<div id='eventAndPriority'>
		<?php include("eventsselect.php")?>
		<div id="priority">
			<label for="priorityList">Priority</label>
			<select id='priorityList'>
				<option value='1'>1 - Highest</option>
				<option value='2'>2</option>
				<option value='3'>3</option>
				<option value='4'>4</option>
				<option value='5'>5 - Lowest</option>
			</select>
		</div>
	</div>
	<?php include("coursesselect.php")?>
</body>
</html>
