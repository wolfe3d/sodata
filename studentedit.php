<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");


//text output
$output = "";

$studentID = intval($_REQUEST['studentID']);
if(empty($studentID))
{
	//no student id was sent, so initiate adding a student
	//check for permissions to add a student
	if($_SESSION['userData']['privilege']<2 )
	{
		echo "You do not have permissions to add a student.";
		exit();
	}
	$defaultYear = date("Y")+4;
	$query = "INSERT INTO `student` (`studentID`, `userID`, `uniqueToken`, `last`, `first`, `active`, `yearGraduating`, `email`, `emailSchool`, `phoneType`, `phone`, `parent1Last`, `parent1First`, `parent1Email`, `parent1Phone`, `parent2Last`, `parent2First`, `parent2Email`, `parent2Phone`) VALUES (NULL, NULL, '', 'last_name', 'first_name', '1', '$defaultYear', '', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
	$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if ($result === TRUE)
	{
		$studentID =  $mysqlConn->insert_id;
	}
	else {
		echo "Failed to add new student.";
		exit();
	}
}

//check to see if user has a valid studentID
$query = "SELECT * FROM `student` WHERE `student`.`studentID` = $studentID";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

//check to make sure the query was valid
if(empty($result))
{
	echo "Query Student Edit Failed.";
	exit();
}

//fill the row with the query data
$row = $result->fetch_assoc();

//Check permissions to make this user is either an admin or editing their own data
if($_SESSION['userData']['privilege']<2 && $_SESSION['userData'][`id`]!=$row['userID'])
{
	echo "The current user does not have privilege for this change.";
	exit;
}



//Check that student row exits from table
if(!$row)
{
	echo "No user found.";
	exit;
}

//find student's events
$query = "SELECT * FROM `eventchoice` t1 INNER JOIN `eventyear` t2 ON t1.`eventID`=t2.`eventID` WHERE `studentID`=$studentID ORDER BY t2.`year` DESC, t1.`priority` ASC";// where `field` = $fieldId";
$resultEventsChoice = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$eventsChoice ="";
if(mysqli_num_rows($resultEventsChoice)>0)
{
	$eventsChoice .="<div>Year-Priority Event Name</div>";
	while ($rowEventsChoice = $resultEventsChoice->fetch_assoc()):
		$eventsChoice .= "<div id='eventChoice-" . $rowEventsChoice['eventChoiceID'] . "'>" . $rowEventsChoice['year'] . "-" . $rowEventsChoice['priority'] . " " . $rowEventsChoice['event'] . " <a href=\"javascript:removeEvent('" . $rowEventsChoice['eventChoiceID'] . "')\">Remove</a></div>";
	endwhile;
}

//get $phoneTypes
$query = "SELECT * from `phonetype`";
$resultPhone = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$phoneTypes="";
if($resultPhone)
{
	while ($rowPhone = $result->fetch_assoc()):
		$phoneTypes.="<option value = '".$row['phoneType']."'>".$row['phoneType']."</option>";
	endwhile;
}

//find student's course completed
$query = "SELECT * FROM `coursecompleted` t1 INNER JOIN `course` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=$studentID ORDER BY t2.`course` ASC";// where `field` = $fieldId";
$resultCourse = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$courseCompleted ="";
if(mysqli_num_rows($resultCourse)>0)
{
	$courseCompleted .="<div>Course Name - Level</div>";
	while ($rowCourse = $resultCourse->fetch_assoc()):
		$courseCompleted .= "<div id='courseCompleted-" . $rowCourse['myID'] . "'>" . $rowCourse['course'] . " - " . $rowCourse['level'] . " <a href=\"javascript:removeCourse('" . $rowCourse['myID'] . "','courseCompleted')\">Remove</a></div>";
	endwhile;
}

//find student's course enrolled but not yet completed
$query = "SELECT * FROM `courseenrolled` t1 INNER JOIN `course` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=$studentID ORDER BY t2.`course` ASC";// where `field` = $fieldId";
$resultCourse = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$courseEnrolled ="";
if(mysqli_num_rows($resultCourse)>0)
{
	$courseEnrolled .="<div>Course Name - Level</div>";
	while ($rowCourse = $resultCourse->fetch_assoc()):
		$courseEnrolled .= "<div id='courseEnrolled-" . $rowCourse['myID'] . "'>" . $rowCourse['course'] . " - " . $rowCourse['level'] . " <a href='' onclick=\"removeCourse('" . $rowCourse['myID'] . "','courseenrolled');return false;\">Remove</a> <a href='' onclick=\"moveCourse('" . $rowCourse['myID'] . "');return false;\">Completed</a></div>";
	endwhile;
}

$privilegeText = editPrivilege(4,$row['userID'],$mysqlConn);
?>
<form id="studentUpdate" method="post" action="studentUpdate.php">
		<fieldset>
			<legend>Edit Student</legend>
			<?php if($_SESSION['userData']['privilege']>2)
			{				?>
			<p>
				<input id="active" name="active" type="checkbox" <?=$row['active']==1?"checked":""?> onchange="studentUpdate(<?=$studentID?>,'student',this.id,+$(this).is(':checked'))"><label for="active">Active</label>
			</p>
		<?php } ?>
			<p>
				<label for="first">Firstname</label>
				<input id="first" name="first" type="text" value="<?=$row['first']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
			</p>
			<p>
				<label for="last">Lastname</label>
				<input id="last" name="last" type="text" value="<?=$row['last']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
			</p>
			<p>
				<label for="yearGraduating">Year Graduating</label>
				<input id="yearGraduating" name="yearGraduating" type="text" value="<?=$row['yearGraduating']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
			</p>
			<p>
				<!--Changing Google Email may break functions TODO: Think about changing this ability-->
				<label for="email">Google Email</label>
				<input id="email" name="email" type="email" value="<?=$row['email']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
			</p>
			<p>
				<label for="emailSchool">School Email</label>
				<input id="emailSchool" name="emailSchool" type="email" value="<?=$row['emailSchool']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
			</p>
			<p>
				<label for="phoneType">Phone Type</label>
				<select id="phoneType" name="text" value="<?=$row['phoneType']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
					<?=$phoneTypes?>
				</select>
			</p>
			<p>
				<label for="phone">Phone</label>
				<input id="phone" name="phone" type="tel" value="<?=$row['phone']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
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
				<div id="courseCompleted"><?=$courseCompleted?></div>
				<div id="addcoursecompletedDiv"></div>
				<a id="addcoursecompleted" class="addCourseBtn" href="javascript:addCourseChoice('<?=$studentID?>','coursecompleted')">Add Course Completed</a>
			</fieldset>
			<fieldset>
				<legend>Courses - Enrolled (but not completed)</legend>
				<div id="courseEnrolled"><?=$courseEnrolled?></div>
				<div id="addcourseenrolledDiv"></div>
				<a id="addcourseenrolled" class="addCourseBtn" href="javascript:addCourseChoice('<?=$studentID?>','courseenrolled')">Add Course Enrolled</a>
			</fieldset>
			<fieldset>
				<legend>Parent 1</legend>
				<p>
					<label for="parent1First">First</label>
					<input id="parent1First" name="parent1First" type="text" value="<?=$row['parent1First']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
				</p>
				<p>
					<label for="parent1Last">Last</label>
					<input id="parent1Last" name="parent1Last" type="text" value="<?=$row['parent1Last']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
				</p>
				<p>
					<label for="parent1Email">Email</label>
					<input id="parent1Email" name="parent1Email" type="email" value="<?=$row['parent1Email']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
				</p>
				<p>
					<label for="parent1Phone">Phone</label>
					<input id="parent1Phone" name="parent1Phone" type="tel" value="<?=$row['parent1Phone']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
				</p>
			</fieldset>
			<fieldset>
				<legend>Parent 2</legend>
				<p>
					<label for="parent2First">First</label>
					<input id="parent2First" name="parent2First" type="text" value="<?=$row['parent2First']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
				</p>
				<p>
					<label for="parent2Last">Last</label>
					<input id="parent2Last" name="parent2Last" type="text" value="<?=$row['parent2Last']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
				</p>
				<p>
					<label for="parent2Email">Email</label>
					<input id="parent2Email" name="parent2Email" type="email" value="<?=$row['parent2Email']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
				</p>
				<p>
					<label for="parent2Phone">Phone</label>
					<input id="parent2Phone" name="parent2Phone" type="tel" value="<?=$row['parent2Phone']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
				</p>
			</fieldset>
		</fieldset>
		<?=$privilegeText ?>
	</form>
	<a href="#user">Back</a>
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
	<?php include("courseselect.php")?>
