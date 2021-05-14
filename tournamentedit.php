<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
require_once  ("functions.php");


//text output
$output = "";

$tournamentID = intval($_REQUEST['tournamentID']);
if(empty($studentID))
{
	//no student id was sent, so initiate adding a student
	$defaultYear = date("Y")+4;
	//TODO: ADD tournament if it does not exist
	//$query = "INSERT INTO `student` (`studentID`, `userID`, `uniqueToken`, `last`, `first`, `active`, `yearGraduating`, `email`, `emailSchool`, `phoneType`, `phone`, `parent1Last`, `parent1First`, `parent1Email`, `parent1Phone`, `parent2Last`, `parent2First`, `parent2Email`, `parent2Phone`) VALUES (NULL, NULL, '', 'last_name', 'first_name', '1', '$defaultYear', '', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
	//$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
$query = "SELECT * from `tournament` INNER JOIN `tournamentinfo` ON `tournament`.`tournamentInfoID`= `tournamentinfo`.`tournamentInfoID` WHERE `tournament`.`tournamentID` = $tournamentID";
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
/*if($_SESSION['userData']['privilege']<2 && $_SESSION['userData'][`id`]!=$row['userID'])
{
	echo "The current user does not have privilege for this change.";
	exit;
}*/
userCheckPrivilege(2);

//Check that student row exits from table
if(!$row)
{
	echo "No user found.";
	exit;
}

?>
<form id="addTo" method="post" action="tournamentUpdate.php">
		<fieldset>
			<legend>Edit Tournament</legend>
			<p>
				<label for="dateTournament">Competition Date</label>
				<input id="dateTournament" name="dateTournament" type="dateTournament" value="<?=$row['dateTournament']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
			</p>
			<p>
				<label for="dateRegistration">Registration Date</label>
				<input id="dateRegistration" name="dateRegistration" type="dateRegistration" value="<?=$row['dateRegistration']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
			</p>
			<p>
				<label for="year">Competition Year (National Rules Year)</label>
				<input id="year" name="year" type="text" value="<?=$row['year']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
			</p>
			<p>
				<label for="type">Type of Competition (Full, Mini, Hybrid, etc.)</label>
				<input id="type" name="type" type="text" value="<?=$row['type']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
			</p>
			<p>
				<label for="numberTeams">Number of Teams Registered</label>
				<input id="numberTeams" name="numberTeams" type="text" value="<?=$row['numberTeams']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
			</p>
			<p>
				<label for="weighting">Weighting</label>
				<input id="weighting" name="weighting" type="text" value="<?=$row['weighting']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
			</p>
			<p>
				<label for="note">Note(s)</label>
				<input id="note" name="note" type="text" value="<?=$row['note']?>" onchange="studentUpdate(<?=$studentID?>,'student',this.id,this.value)">
			</p>
			<fieldset>
				<legend>Tournament Information</legend>
				<div id="name"></div>
				<a id="name" href="javascript:studentEventAddChoice('<?=$studentID?>')" href="">Add Event</a>
			</fieldset>
			<fieldset>
				<legend>Host</legend>
				<div id="host"><?= getCourses($mysqlConn, $studentID, "coursecompleted")?></div>
				<div id="addcoursecompletedDiv"></div>
				<a id="addcoursecompleted" class="addCourseBtn" href="javascript:studentCourseAddChoice('<?=$studentID?>','coursecompleted')">Add Course Completed</a>
			</fieldset>
			<fieldset>
				<legend>Courses Enrolled (but not completed)</legend>
				<div id="courseenrolled"><?= getCourses($mysqlConn, $studentID, "courseenrolled")?></div>
				<div id="addcourseenrolledDiv"></div>
				<a id="addcourseenrolled" class="addCourseBtn" href="javascript:studentCourseAddChoice('<?=$studentID?>','courseenrolled')">Add Course Enrolled</a>
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
