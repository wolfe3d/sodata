<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
require_once  ("functions.php");

//text output
$output = "";

$last = isset($_POST['last'])?$mysqlConn->real_escape_string($_POST['last']):0;
$first = isset($_POST['first'])?$mysqlConn->real_escape_string($_POST['first']):0;
$eventPriorityID = intval($_POST['eventPriority']);
$eventCompetitionID = intval($_POST['eventCompetition']);
$courseID = isset($_POST['courseList'])?intval($_POST['courseList']):0;
$active = intval($_POST['active']);

$activeQuery ="";
if($active)
{
	$activeQuery = " `student`.`active` = 1 AND ";
}
$query = "SELECT * from `student`";
//check to see what is searched for
if($last&&$first)
{
	$query .= " where $activeQuery `student`.`last` LIKE '%$last%' AND `student`.`first` LIKE '%$first%'";
}
else if($last)
{
	$query .= " where $activeQuery `student`.`last` LIKE '%$last%'";
}
else if($first)
{
	$query .= " where $activeQuery `student`.`first` LIKE '%$first%'";
}
else if($active && !$courseID && !$eventPriorityID && !$eventCompetitionID)
{
	$query .= " where `student`.`active` = 1";
}

if($eventPriorityID)
{
	//Search for student signed up for event
	//$query = "SELECT DISTINCT `student`.`studentID` from `student` `student` INNER JOIN `eventchoice` t2 ON `student`.`studentID`=t2.`studentID` INNER JOIN `eventyear` t3 ON t2.`eventID`=t3.`eventID` WHERE t3.`event` LIKE '$eventName'";
	$eventQuery = "SELECT DISTINCT `student`.`studentID` from `student` INNER JOIN `eventchoice` ON `student`.`studentID`=`eventchoice`.`studentID` INNER JOIN `eventyear` ON `eventchoice`.`eventyearID`=`eventyear`.`eventyearID` WHERE $activeQuery `eventyear`.`eventID`=$eventPriorityID";
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
		$query.=" where $activeQuery `student`.`studentID` IN ($studentIDs)";
		echo userHasPrivilege(3)?$query:"";
	}
	else {
		echo "No one is signed up for this event($eventPriorityID).";
		return 0;
	}
}

if($eventCompetitionID)
{
	//Search for student who competed in this event
	$eventQuery = "SELECT DISTINCT `student`.`studentID` from `student` INNER JOIN `teammateplace` ON `student`.`studentID`=`teammateplace`.`studentID` INNER JOIN `event` ON `teammateplace`.`tournamenteventID`=`event`.`eventID` WHERE $activeQuery `event`.`eventID`=$eventCompetitionID";
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
		$query.=" where $activeQuery `student`.`studentID` IN ($studentIDs)";
		echo userHasPrivilege(3)?$query:"";
	}
	else {
		echo "No one has competed in this event($eventCompetitionID).";
		return 0;
	}
}

if($courseID)
{
	//Search for student signed up for event
	//$query = "SELECT DISTINCT `student`.`studentID` from `student` `student` INNER JOIN `eventchoice` t2 ON `student`.`studentID`=t2.`studentID` INNER JOIN `eventyear` t3 ON t2.`eventID`=t3.`eventID` WHERE t3.`event` LIKE '$eventName'";
	$eventQuery = "SELECT DISTINCT `student`.`studentID` from `student` INNER JOIN `coursecompleted` ON `student`.`studentID`=`coursecompleted`.`studentID` INNER JOIN `courseenrolled` ON `student`.`studentID`=`courseenrolled`.`studentID` WHERE $activeQuery `coursecompleted`.`courseID`=$courseID OR `courseenrolled`.`courseID`=$courseID ";
	$output .=$eventQuery;
	$result = $mysqlConn->query($eventQuery) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$studentIDs="";
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
		$query.=" where $activeQuery `student`.`studentID` IN ($studentIDs)";
	}
	else {
		echo "No one is signed up for this course with ID=$courseID.";
		return 0;
	}
}

$query .= " ORDER BY `student`.`last` ASC";
$output .= userHasPrivilege(3)?$query:"";
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

		if(userHasPrivilege(2)) //||$_SESSION['userData']['id']==$row['userID']) //Users cannot edit their own information
		{
			$output .="<div><a href='#student-edit-".$row['studentID']."'>Edit</a> ";
		}
		$output .= userHasPrivilege(3)?"<a href=\"javascript:studentRemove(" . $row['studentID'] . ",'" . $row['first']." ".$row['last'] . "')\">Remove</a>":"";
		$output .= "</div>";
		//show privilege
		if(userHasPrivilege(3))
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
		$grade = 9;
		if (date("m")>5)
		{
			$grade = 12-($row['yearGraduating']-date("Y")-1);
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
			$output .="<div>Phone(".$row['phoneType']."): ".$row['phone']."</div>";
		}
		$officerPosPrev = getPreviousOfficerPosition($mysqlConn,$row['studentID']);
		if($officerPosPrev)
		{
			$output .="<div>Previous Postions: $officerPosPrev</div>";
		}
		if($row['parent1Last'])
		{
			$output .="<br><h3>Parent(s)</h3>";
			$output .="<div>".$row['parent1First']." ".$row['parent1Last'].", ".$row['parent1Email'].", ".$row['parent1Phone']."</div>";
			if($row['parent2Last'])
			{
				$output .="<div>".$row['parent2First']." ".$row['parent2Last'].", ".$row['parent2Email'].", ".$row['parent2Phone']."</div>";
			}
		}
		//find student's event priority
		$query = "SELECT * FROM `eventchoice` INNER JOIN `eventyear` ON `eventchoice`.`eventyearID`=`eventyear`.`eventyearID` INNER JOIN `event` ON `eventyear`.`eventID`=`event`.`eventID` WHERE `eventchoice`.`studentID`=".$row['studentID']." ORDER BY `eventyear`.`year` DESC, `eventchoice`.`priority` ASC";// where `field` = $fieldId";
		$resultEventsPriority = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if (mysqli_num_rows($resultEventsPriority)>0)
		{
				$output .="<br><h3>Event Priority</h3>";
				while ($rowEventsPriority = $resultEventsPriority->fetch_assoc()):
					$output .= "<div id='eventPriority-" . $rowEventsPriority['eventChoiceID'] . "'>" . $rowEventsPriority['year'] . "-" . $rowEventsPriority['priority'] . " " . $rowEventsPriority['event'] . "</div>";
				endwhile;
		}

		//find student's events that they competed in
		$query = "SELECT * FROM `teammateplace` INNER JOIN `event` ON `teammateplace`.`tournamenteventID`=`event`.`eventID` WHERE `teammateplace`.`studentID`=".$row['studentID']." ORDER BY `event`.`event` ASC";// where `field` = $fieldId";
		$resultEventsCompetition = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if (mysqli_num_rows($resultEventsCompetition)>0)
		{
				$output .="<br><h3>Events Competed</h3>";
				while ($rowEventsCompetition = $resultEventsCompetition->fetch_assoc()):
					//check partner
					$query = "SELECT * FROM `student` INNER JOIN `teammateplace` ON `student`.`studentID`=`teammateplace`.`studentID` WHERE `teammateplace`.`tournamenteventID`=".$rowEventsCompetition['tournamenteventID']." AND `teammateplace`.`teamID`=".$rowEventsCompetition['teamID']." AND NOT `teammateplace`.`studentID` = ".$rowEventsCompetition['studentID']." ORDER BY `student`.`last` ASC, `student`.`first` ASC";
					$resultPartners = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
					$partners ="";
					if ($resultPartners && mysqli_num_rows($resultPartners)>0)
					{
						while ($rowPartner = $resultPartners->fetch_assoc()):
							$partners.=$partners?" AND ":"";
							$partners.=$rowPartner['first']." ".$rowPartner['last'];
						endwhile;
						if(empty($partners)){
							$partners="No partners";
						}
					}
					$output .= "<div id='eventCompetition-" . $rowEventsCompetition['eventID'] . "'>" . $rowEventsCompetition['event'] . " place " . $rowEventsCompetition['place'] . " ($partners)</div>";
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



	$output .="</div>";
endwhile; // end loop through student list
	//complete enclosing div
}
echo $output;
?>
