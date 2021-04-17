<?php
require_once ("../connectsodb.php");
//text output
$output = "";


$last = $mysqlConn->real_escape_string($_REQUEST['last']);
$first = $mysqlConn->real_escape_string($_REQUEST['first']);
$eventID = intval(($_REQUEST['eventsList']);
$courseID = intval(($_REQUEST['coursesList']);

$query = "SELECT * from `students`";
//check to see what is searched for
if($last&&$first)
{
	$query .= " where `last` LIKE '$last' AND `first` LIKE '$first' ORDER BY `last` ASC";
}
else if($last)
{
	$query .= " where `last` LIKE '$last' ORDER BY `last` ASC";
}
else if($first)
{
	$query .= "where `first` LIKE '$first' ORDER BY `first` ASC";
}
else
{
	//
}

$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$output .="<hr><h2>".$row['first']." ".$row['last']."</h2>";
		$output .="<div><a href='studentedit.php?studentID=".$row['studentID']."'>Edit</a></div>";
		$grade = 9;
		if (date("M")>5)
		{
			$grade = 12-($row['yearGraduating']-date("Y")+1);
		}
		else
		{
			$grade = 12-($row['yearGraduating']-date("Y"));
		}
		$output .="<div>Grade: $grade (".$row['yearGraduating'].")</div>";
		if($row['email'])
		{
			$output .="<div>Preferred Email:".$row['email']."</div>";
		}
		if($row['emailAlt'])
		{
			$output .="<div>Alternate Email:".$row['emailAlt']."</div>";
		}
		if($row['phone'])
		{
			$output .="<div>Phone(".$row['phoneType']."):".$row['phone']."</div>";
		}
		if($row['parent1Last'])
		{
			$output .="<h3>Parent(s)</h3>";
			$output .="<div>".$row['parent1First']." ".$row['parent1Last'].",".$row['parent1Email'].",".$row['parent1Phone']."</div>";
			if($row['parent2Last'])
			{
				$output .="<div>".$row['parent2First']." ".$row['parent2Last'].",".$row['parent2Email'].",".$row['parent2Phone']."</div>";
			}
		}
		//find student's events
		$query = "SELECT * FROM `eventschoice` t1 INNER JOIN `eventsyear` t2 ON t1.`eventID`=t2.`eventID` WHERE `studentID`=".$row['studentID'];// where `field` = $fieldId";
		$resultEventsChoice = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if (mysqli_num_rows($resultEventsChoice)>0)
		{
				$output .="<h3>Events</h3>";
				while ($rowEventsChoice = $resultEventsChoice->fetch_assoc()):
					$output .= "<div id='eventChoice-" . $rowEventsChoice['eventsChoiceID'] . "'>" . $rowEventsChoice['year'] . " " . $rowEventsChoice['event'] . "</div>";
				endwhile;
		}

		//find student's courses completed
		$query = "SELECT * FROM `coursescompleted` t1 INNER JOIN `courses` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=".$row['studentID']." ORDER BY t2.`course` ASC";// where `field` = $fieldId";
		$resultCourses = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if(mysqli_num_rows($resultCourses)>0)
		{
			$output .="<h3>Course Completed - Level</h3>";
			while ($rowCourse = $resultCourses->fetch_assoc()):
				$output .= "<div id='coursesCompleted-" . $rowCourse['myID'] . "'>" . $rowCourse['course'] . " - " . $rowCourse['level'] . "</div>";
			endwhile;
		}

		//find student's courses enrolled but not yet completed
		$query = "SELECT * FROM `coursesenrolled` t1 INNER JOIN `courses` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=".$row['studentID']." ORDER BY t2.`course` ASC";// where `field` = $fieldId";
		$resultCourses = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if(mysqli_num_rows($resultCourses)>0)
		{
			$output .="<h3>Courses Enrolled - Level</h3>";
			while ($rowCourse = $resultCourses->fetch_assoc()):
				$output .= "<div id='coursesEnrolled-" . $rowCourse['myID'] . "'>" . $rowCourse['course'] . " - " . $rowCourse['level'] . "</div>";
			endwhile;
		}
	endwhile;
	$output .="</div>";
}
echo $output;
?>
