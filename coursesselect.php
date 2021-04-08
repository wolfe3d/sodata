<?php
require_once  ("../connectsodb.php");

//get list of events
$query = "SELECT * FROM `courses` ORDER BY `course` ASC";// where `field` = $fieldId";
$resultCoursesList = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$courses ="<div id='coursesListDiv'><label for='coursesList'>Courses</label> ";
$courses .="<select id='coursesList'>";
	if($resultCoursesList)
	{
		while ($rowCourses = $resultCoursesList->fetch_assoc()):
			$courses .= "<option value='" . $rowCourses['courseID'] . "'>" . $rowCourses['course'] . " - " . $rowCourses['level'] . "</option>";
		endwhile;
	}
	$courses.="</select></div>";
echo $courses;
?>
