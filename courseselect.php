<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

//get list of events
$query = "SELECT * FROM `course` ORDER BY `course` ASC";// where `field` = $fieldId";
$resultCourseList = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$courses ="<div id='courseListDiv'><label for='courseList'>Courses</label> ";
$courses .="<select id='courseList'>";
	if($resultCourseList)
	{
		while ($row = $resultCourseList->fetch_assoc()):
			$courses .= "<option value='" . $row['courseID'] . "'>" . $row['course'] . " - " . $row['level'] . "</option>";
		endwhile;
	}
	$courses.="</select></div>";
echo $courses;
?>
