<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");

//text output
$output = "";


$last = isset($_POST['last'])?$mysqlConn->real_escape_string($_POST['last']):0;
$first = isset($_POST['first'])?$mysqlConn->real_escape_string($_POST['first']):0;
$eventName = isset($_POST['eventsList'])?$mysqlConn->real_escape_string($_POST['eventsList']):0;
$courseID = isset($_POST['courseList'])?intval($_POST['courseList']):0;
$active = intval($_POST['active']);

$activeQuery ="";
if($active)
{
	$activeQuery = " t1.`active` = 1 AND ";
}

$query = "SELECT * from `student` t1";
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
	//$query = "SELECT DISTINCT t1.`studentID` from `student` t1 INNER JOIN `eventchoice` t2 ON t1.`studentID`=t2.`studentID` INNER JOIN `eventyear` t3 ON t2.`eventID`=t3.`eventID` WHERE t3.`event` LIKE '$eventName'";
	$eventQuery = "SELECT DISTINCT t1.`studentID` from `student` t1 INNER JOIN `eventchoice` t2 ON t1.`studentID`=t2.`studentID` INNER JOIN `eventyear` t3 ON t2.`eventID`=t3.`eventID` WHERE $activeQuery t3.`event` LIKE '$eventName'";
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
	//$query = "SELECT DISTINCT t1.`studentID` from `student` t1 INNER JOIN `eventchoice` t2 ON t1.`studentID`=t2.`studentID` INNER JOIN `eventyear` t3 ON t2.`eventID`=t3.`eventID` WHERE t3.`event` LIKE '$eventName'";
	$eventQuery = "SELECT DISTINCT t1.`studentID` from `student` t1 INNER JOIN `coursecompleted` t2 ON t1.`studentID`=t2.`studentID` INNER JOIN `courseenrolled` t3 ON t2.`studentID`=t3.`studentID` WHERE $activeQuery t2.`courseID`=$courseID OR t3.`courseID`=$courseID ";
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
	while ($row = $result->fetch_assoc()):
		$output .="<div id='student-". $row['studentID'] ."'>";
		$output .="<hr><h2>".$row['first']." ".$row['last']."</h2>";
		$officerPos = getOfficerPosition($mysqlConn,$row['studentID']);
		if($officerPos)
		{
			$output .="<h3>$officerPos</h3>";
		}
		if($_SESSION['userData']['privilege']>1||$_SESSION['userData']['id']==$row['userID'])
		{
			$output .="<div><a href='javascript:studentEdit(".$row['studentID'].")'>Edit</a> ";
		}
		$output .= $_SESSION['userData']['privilege']>3?"<a href=\"javascript:studentRemove(" . $row['studentID'] . ",'" . $row['first']." ".$row['last'] . "')\">Remove</a>":"";
		$output .= "</div>";
		$grade = 9;
		if (date("m")>5)
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
			$output .="<div>Google Email: <a href='mailto: ".$row['email']."'>".$row['email']."</a></div>";
		}
		if($row['emailSchool'])
		{
			$output .="<div>School Email: <a href='mailto: ".$row['emailSchool']."'>".$row['emailSchool']."</a></div>";
		}
		if($row['phone'])
		{
			$output .="<div>Phone(".$row['phoneType']."):".$row['phone']."</div>";
		}
		$officerPosPrev = getPreviousOfficerPosition($mysqlConn,$row['studentID']);
		if($officerPosPrev)
		{
			$output .="<div>Previous Postions: $officerPosPrev</div>";
		}
		if($row['parent1Last'])
		{
			$output .="<br><h3>Parent(s)</h3>";
			$output .="<div>".$row['parent1First']." ".$row['parent1Last'].",".$row['parent1Email'].",".$row['parent1Phone']."</div>";
			if($row['parent2Last'])
			{
				$output .="<div>".$row['parent2First']." ".$row['parent2Last'].",".$row['parent2Email'].",".$row['parent2Phone']."</div>";
			}
		}
		//find student's events
		$query = "SELECT * FROM `eventchoice` t1 INNER JOIN `eventyear` t2 ON t1.`eventID`=t2.`eventID` WHERE `studentID`=".$row['studentID'];// where `field` = $fieldId";
		$resultEventsChoice = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if (mysqli_num_rows($resultEventsChoice)>0)
		{
				$output .="<br><h3>Events</h3>";
				while ($rowEventsChoice = $resultEventsChoice->fetch_assoc()):
					$output .= "<div id='eventChoice-" . $rowEventsChoice['eventChoiceID'] . "'>" . $rowEventsChoice['year'] . "-" . $rowEventsChoice['priority'] . " " . $rowEventsChoice['event'] . "</div>";
				endwhile;
		}

		//find student's courses completed
		$query = "SELECT * FROM `coursecompleted` t1 INNER JOIN `course` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=".$row['studentID']." ORDER BY t2.`course` ASC";// where `field` = $fieldId";
		$resultCourse = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if(mysqli_num_rows($resultCourse)>0)
		{
			$output .="<br><h3>Courses Completed - Level</h3>";
			while ($rowCourse = $resultCourse->fetch_assoc()):
				$output .= "<div id='courseCompleted-" . $rowCourse['myID'] . "'>" . $rowCourse['course'] . " - " . $rowCourse['level'] . "</div>";
			endwhile;
		}

		//find student's courses enrolled but not yet completed
		$query = "SELECT * FROM `courseenrolled` t1 INNER JOIN `course` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=".$row['studentID']." ORDER BY t2.`course` ASC";// where `field` = $fieldId";
		$resultCourse = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if(mysqli_num_rows($resultCourse)>0)
		{
			$output .="<br><h3>Courses Enrolled - Level</h3>";
			while ($rowCourse = $resultCourse->fetch_assoc()):
				$output .= "<div id='courseEnrolled-" . $rowCourse['myID'] . "'>" . $rowCourse['course'] . " - " . $rowCourse['level'] . "</div>";
			endwhile;
		}


	//show privilege
	if($_SESSION['userData']['privilege']>2)
	{
		if(empty($row['userID']))
		{
				$output .= "User has never logged in with registered account.";
		}
		else {
			$query = "SELECT * FROM `user` WHERE `id`=".$row['userID'];// where `field` = $fieldId";
			$ouput .= $query;
			$resultPrivilege = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
			$rowPriv = $resultPrivilege->fetch_assoc();
			if ($rowPriv['privilege'])
			{
				$output .= "<div>Privilege: ".$rowPriv['privilege']."</div>";
			}
			else
			{
				$output .= "User has never logged in with registered account.";
			}
		}
	}
	$output .="</div>";
endwhile; // end loop through student list
	//complete enclosing div
}
echo $output;
?>
