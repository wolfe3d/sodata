<?php
require_once  ("functions.php");
 ?>

<?php if($row)
{?>
<p>
    <input id="active" name="active" type="checkbox" <?=$row['active']==1?"checked":""?>><label for="active">Active</label>
</p>
<?php 
}?>

<p>
    <label for="first">Firstname</label>
    <input id="first" name="first" type="text" value="<?=$row['first']?>">
</p>
<p>
    <label for="last">Lastname</label>
    <input id="last" name="last" type="text" value="<?=$row['last']?>">
</p>
<p>
    <label for="yearGraduating">Year Graduating</label>
    <input id="yearGraduating" name="yearGraduating" type="text" value="<?=$row['yearGraduating']?>">
</p>
<p>
<!--Changing Google Email may break functions TODO: Think about changing this ability-->
    <label for="email">Google Email</label>
    <input id="email" name="email" type="email" value="<?=$row['email']?>">
</p>
<p>
    <label for="emailSchool">School Email</label>
    <input id="emailSchool" name="emailSchool" type="email" value="<?=$row['emailSchool']?>">
</p>
<p>
    <label for="phoneType">Phone Type</label>
    <select id="phoneType" name="text" value="<?=$row['phoneType']?>">
        <?=getPhoneTypes($mysqlConn)?>
    </select>
</p>
<p>
    <label for="phone">Phone</label>
    <input id="phone" name="phone" type="tel" value="<?=$row['phone']?>">
</p>
<?php if($row)
{?>
    <fieldset>
        <legend>Events</legend>
        <div id="events"><?=$eventsChoice?></div>
        <div id="studentEventAddDiv"></div>
        <a id="studentEventAdd" href="javascript:studentEventAddChoice('<?=$studentID?>')" href="">Add Event</a>
    </fieldset>
    <fieldset>
        <legend>Courses Completed</legend>
        <div id="coursecompleted"><?= getCourses($mysqlConn, $studentID, "coursecompleted")?></div>
        <div id="addcoursecompletedDiv"></div>
        <a id="addcoursecompleted" class="addCourseBtn" href="javascript:studentCourseAddChoice('<?=$studentID?>','coursecompleted')">Add Course Completed</a>
    </fieldset>
    <fieldset>
        <legend>Courses Enrolled (but not completed)</legend>
        <div id="courseenrolled"><?= getCourses($mysqlConn, $studentID, "courseenrolled")?></div>
        <div id="addcourseenrolledDiv"></div>
        <a id="addcourseenrolled" class="addCourseBtn" href="javascript:studentCourseAddChoice('<?=$studentID?>','courseenrolled')">Add Course Enrolled</a>
    </fieldset>
<?php 
}?>
<fieldset>
    <legend>Parent 1</legend>
    <p>
        <label for="parent1First">First</label>
        <input id="parent1First" name="parent1First" type="text" value="<?=$row['parent1First']?>">
    </p>
    <p>
        <label for="parent1Last">Last</label>
        <input id="parent1Last" name="parent1Last" type="text" value="<?=$row['parent1Last']?>">
    </p>
    <p>
        <label for="parent1Email">Email</label>
        <input id="parent1Email" name="parent1Email" type="email" value="<?=$row['parent1Email']?>">
    </p>
    <p>
        <label for="parent1Phone">Phone</label>
        <input id="parent1Phone" name="parent1Phone" type="tel" value="<?=$row['parent1Phone']?>">
    </p>
</fieldset>
<fieldset>
<legend>Parent 2</legend>
<p>
    <label for="parent2First">First</label>
    <input id="parent2First" name="parent2First" type="text" value="<?=$row['parent2First']?>">
</p>
<p>
    <label for="parent2Last">Last</label>
    <input id="parent2Last" name="parent2Last" type="text" value="<?=$row['parent2Last']?>">
</p>
<p>
    <label for="parent2Email">Email</label>
    <input id="parent2Email" name="parent2Email" type="email" value="<?=$row['parent2Email']?>">
</p>
<p>
    <label for="parent2Phone">Phone</label>
    <input id="parent2Phone" name="parent2Phone" type="tel" value="<?=$row['parent2Phone']?>">
</p>
</fieldset>