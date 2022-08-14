<?php
require_once  ("php/functions.php");
userCheckPrivilege(2);
$studentID = 1; //TODO: CHange me

$editing = true;
if(!isset($row))
{
	$editing = false;
	$row = NULL;
}
?>
<h3><?=getCurrentSchoolName($mysqlConn)?></h3>
<?php
if ($editing)
{
	?>

	<p>
		<input id="active" name="active" class="form-check-input" type="checkbox" <?=$row['active']==1?"checked":""?>><label for="active">Active</label>
	</p>
	<?php
} ?>
<p>
	<label for="first">Firstname</label>
	<input id="first" name="first" class="form-control" type="text" value="<?=$row['first']?>" required>
</p>
<p>
	<label for="last">Lastname</label>
	<input id="last" name="last" class="form-control" type="text" value="<?=$row['last']?>" required>
</p>
<?php
if (userHasPrivilege(4))
{
	?>
	<p>
		<input id="paidDues" name="paidDues" class="form-check-input" type="checkbox" <?=$row['paidDues']==1?"checked":""?>><label for="paidDues">Dues Paid</label>

		<label for="paidDuesDate">on Date</label>
		<input id="paidDuesDate" name="paidDuesDate" class="form-control" type="date" value="<?=$row['paidDuesDate']?>">
	</p>
	<?php
}
?>
<p>
	<label for="yearGraduating">Year Graduating</label>
	<input id="yearGraduating" name="yearGraduating" class="form-control" type="text" min="1982" value="<?=$row['yearGraduating']?>" required>
</p>
<p>
	<label for="studentschoolID">Student's School ID</label>
	<input id="studentschoolID" name="studentschoolID" class="form-control" type="text" value="<?=$row['studentschoolID']?>">
</p>
<p>
	<label for="scilympiadID"><a href="https://scilympiad.com/">Scilympiad</a> ID</label>
	<input id="scilympiadID" name="scilympiadID" class="form-control" type="text" value="<?=$row['scilympiadID']?>">
</p>
<p>
	<!--Changing Google Email may break functions TODO: Think about changing this ability-->
	<label for="email">Google Email</label>
	<input id="email" name="email" type="email" class="form-control" value="<?=$row['email']?>" required>
</p>
<p>
	<label for="emailSchool">School Email</label>
	<input id="emailSchool" name="emailSchool" class="form-control" type="email" value="<?=$row['emailSchool']?>" required>
</p>
<p>
	<label for="phoneType">Phone Type</label>
	<select id="phoneType" name="phoneType" class="form-control" value="<?=$row['phoneType']?>">
		<option value='0' <?=getSelected(0,$row['phoneType'])?>><?=getPhoneString(0)?></option>
		<option value='1' <?=getSelected(1,$row['phoneType'])?>><?=getPhoneString(1)?></option>
		<option value='2' <?=getSelected(2,$row['phoneType'])?>><?=getPhoneString(2)?></option>
	</select>
</p>
<p>
	<label for="phone">Phone (Format: 555-555-5555)</label>
	<input id="phone" name="phone" class="form-control" placeholder="555-555-5555" type="tel" pattern="^\d{3}-\d{3}-\d{4}$" value="<?=$row['phone']?>" required>
</p>
<?php if($editing)
{?>
	<fieldset>
		<legend>Events</legend>
		<div id="events"><?=$eventsChoice?></div>
		<div id="studentEventAddDiv"></div>
		<a id="studentEventAdd" href="javascript:studentEventAddChoice('<?=$row['studentID']?>')" href="">Add Event</a>
	</fieldset>
	<fieldset>
		<legend>Courses Completed</legend>
		<div id="coursecompleted"><?= getCourses($mysqlConn, $row['studentID'], "coursecompleted")?></div>
		<div id="addcoursecompletedDiv"></div>
		<a id="addcoursecompleted" class="addCourseBtn" href="javascript:studentCourseAddChoice('<?=$row['studentID']?>','coursecompleted')">Add Course Completed</a>
	</fieldset>
	<fieldset>
		<legend>Courses Enrolled (but not completed)</legend>
		<div id="courseenrolled"><?= getCourses($mysqlConn, $row['studentID'], "courseenrolled")?></div>
		<div id="addcourseenrolledDiv"></div>
		<a id="addcourseenrolled" class="addCourseBtn" href="javascript:studentCourseAddChoice('<?=$row['studentID']?>','courseenrolled')">Add Course Enrolled</a>
	</fieldset>
	<?php
}?>
<fieldset>
	<legend>Parent 1</legend>
	<p>
		<label for="parent1First">First</label>
		<input id="parent1First" name="parent1First" class="form-control" type="text" value="<?=$row['parent1First']?>" required>
	</p>
	<p>
		<label for="parent1Last">Last</label>
		<input id="parent1Last" name="parent1Last" class="form-control" type="text" value="<?=$row['parent1Last']?>" required>
	</p>
	<p>
		<label for="parent1Email">Email</label>
		<input id="parent1Email" name="parent1Email" class="form-control" type="email" value="<?=$row['parent1Email']?>" required>
	</p>
	<p>
		<label for="parent1Phone">Phone (Format: 555-555-5555)</label>
		<input id="parent1Phone" name="parent1Phone" class="form-control"placeholder="555-555-5555" type="tel" pattern="^\d{3}-\d{3}-\d{4}$" placeholder="555-555-5555" type="tel" pattern="^\d{3}-\d{3}-\d{4}$" value="<?=$row['parent1Phone']?>" required>
	</p>
</fieldset>
<fieldset>
	<legend>Parent 2</legend>
	<p>
		<label for="parent2First">First</label>
		<input id="parent2First" name="parent2First" class="form-control" type="text" value="<?=$row['parent2First']?>">
	</p>
	<p>
		<label for="parent2Last">Last</label>
		<input id="parent2Last" name="parent2Last" class="form-control" type="text" value="<?=$row['parent2Last']?>">
	</p>
	<p>
		<label for="parent2Email">Email</label>
		<input id="parent2Email" name="parent2Email" class="form-control" type="email" value="<?=$row['parent2Email']?>">
	</p>
	<p>
		<label for="parent2Phone">Phone (Format: 555-555-5555)</label>
		<input id="parent2Phone" name="parent2Phone" class="form-control" placeholder="555-555-5555" type="tel" pattern="^\d{3}-\d{3}-\d{4}$" value="<?=$row['parent2Phone']?>">
	</p>
</fieldset>
