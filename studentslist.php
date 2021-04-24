<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

//text output
$output = "";


$last = $mysqlConn->real_escape_string($_POST['last']);
$first = $mysqlConn->real_escape_string($_POST['first']);
$eventName = $mysqlConn->real_escape_string($_POST['eventsList']);
$courseID = intval($_POST['coursesList']);
$active = intval($_POST['active']);

$activeQuery ="";
if($active)
{
	$activeQuery = " t1.`active` = 1 AND ";
}

$query = "SELECT * from `students` t1";
//check to see what is searched for
if($last&&$first)
{
	$query .= " where $activeQuery t1.`last` LIKE '$last' AND t1.`first` LIKE '$first'";
}
else if($last)
{
	$query .= " where $activeQuery  t1.`last` LIKE '$last'";
}
else if($first)
{
	$query .= " where $activeQuery t1.`first` LIKE '$first'";
}
else if($active && !$courseID && !$eventName)
{
	$query .= " where t1.`active` = 1";
}

if($eventName)
{
	//Search for student signed up for event
	//TODO: Also search for students who have competed in events previously
	//$query = "SELECT DISTINCT t1.`studentID` from `students` t1 INNER JOIN `eventschoice` t2 ON t1.`studentID`=t2.`studentID` INNER JOIN `eventsyear` t3 ON t2.`eventID`=t3.`eventID` WHERE t3.`event` LIKE '$eventName'";
	$eventQuery = "SELECT DISTINCT t1.`studentID` from `students` t1 INNER JOIN `eventschoice` t2 ON t1.`studentID`=t2.`studentID` INNER JOIN `eventsyear` t3 ON t2.`eventID`=t3.`eventID` WHERE $activeQuery t3.`event` LIKE '$eventName'";
	$output .=$eventQuery;
	$result = $mysqlConn->query($eventQuery) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$studentIDs = "";
	while ($row = $result->fetch_assoc()):
		//make array of results
		if($studentIDs !="")
		{
			$studentIDs .=",";
		}
		$studentIDs .= $row['studentID'];
	endwhile;
	//output individual students who have signed up for an event
	if($studentIDs !="")
	{
		$query.=" where $activeQuery t1.`studentID` IN ($studentIDs)";
		echo $query;
	}
	else {
		echo "No one is signed up for the $eventName.";
		return 0;
	}
}

if($courseID)
{
	//Search for student signed up for event
	//$query = "SELECT DISTINCT t1.`studentID` from `students` t1 INNER JOIN `eventschoice` t2 ON t1.`studentID`=t2.`studentID` INNER JOIN `eventsyear` t3 ON t2.`eventID`=t3.`eventID` WHERE t3.`event` LIKE '$eventName'";
	$eventQuery = "SELECT DISTINCT t1.`studentID` from `students` t1 INNER JOIN `coursesCompleted` t2 ON t1.`studentID`=t2.`studentID` INNER JOIN `coursesEnrolled` t3 ON t2.`studentID`=t3.`studentID` WHERE$activeQuery t2.`courseID`=$courseID OR t3.`courseID`=$courseID ";
	$output .=$eventQuery;
	$result = $mysqlConn->query($eventQuery) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$studentIDs = "";
	while ($row = $result->fetch_assoc()):
		//make array of results
		if($studentIDs !="")
		{
			$studentIDs .=",";
		}
		$studentIDs .= $row['studentID'];
	endwhile;
	//output individual students who have signed up for an event
	if($studentIDs !="")
	{
		$query.=" where $activeQuery t1.`studentID` IN ($studentIDs)";
	}
	else {
		echo "No one is signed up for this course with ID=$courseID.";
		return 0;
	}
}

$query .= " ORDER BY t1.`last` ASC";
$output .=$query;
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$output .="<hr><h2>".$row['first']." ".$row['last']."</h2>";
		$output .="<div><a href='studentedit.php?studentID=".$row['studentID']."'>Edit</a>";
		$output .= $_SESSION['userData']['privilege']>2?"<a href='studentRemove(".$row['studentID'].")'>Remove</a>":"";
		$output .= "</div>";
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
