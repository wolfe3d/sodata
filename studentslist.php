<?php
require_once  ("php/functions.php");
userCheckPrivilege(1);

//text output
$output = "";
echo print_r($_POST);
$last = isset($_POST['last'])?$mysqlConn->real_escape_string($_POST['last']):0;
$first = isset($_POST['first'])?$mysqlConn->real_escape_string($_POST['first']):0;
$eventPriorityID = isset($_POST['eventPriority'])?intval($_POST['eventPriority']):0;
$eventCompetitionID = isset($_POST['eventCompetition'])?intval($_POST['eventCompetition']):0;
echo "eC".$eventCompetitionID;
$courseID = isset($_POST['courseList'])?intval($_POST['courseList']):0;
$active = isset($_POST['active'])?intval($_POST['active']):0;
$year = getCurrentSOYear();

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
	if (userHasPrivilege(4))
	{
		echo $eventQuery;
	}
	$result = $mysqlConn->query($eventQuery) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
	$eventQuery = "SELECT DISTINCT `student`.`studentID` from `student`
	INNER JOIN `teammateplace` ON `student`.`studentID`=`teammateplace`.`studentID`
	INNER JOIN `tournamentevent` ON `tournamentevent`.`tournamenteventID`=`teammateplace`.`tournamenteventID`
	INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID`
	 WHERE $activeQuery `event`.`eventID`=$eventCompetitionID";
	if (userHasPrivilege(4))
	{
		echo $eventQuery;
	}
	$result = $mysqlConn->query($eventQuery) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
	if (userHasPrivilege(4))
	{
		echo $eventQuery;
	}
	$result = $mysqlConn->query($eventQuery) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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

$query .= " ORDER BY `student`.`last`, `student`.`first`";
$output .= userHasPrivilege(3)?$query:"";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	while ($row = $result->fetch_assoc()):
		$output .="<div id='student-". $row['studentID'] ."'>";
		$output .="<hr><h2>".$row['first']." ".$row['last']."</h2>";
		$officerPos = getOfficerPosition($mysqlConn,$row['studentID']);
		if($officerPos)
		{
			$output .="<h3>Officer: $officerPos</h3>";
		}
		$eventLeaderPos = getEventLeaderPosition($mysqlConn,$row['studentID'],$year);
		if($eventLeaderPos)
		{
			$output .="<h3>Leading Event(s): $eventLeaderPos</h3>";
		}

		if(userHasPrivilege(3)) //||$_SESSION['userData']['id']==$row['userID']) //Users cannot edit their own information
		{
			$output .="<a class='btn btn-warning' role='button' href='#student-edit-".$row['studentID']."'><span class='bi bi-pencil-square'></span> Edit</a>";
		}
		$output .= userHasPrivilege(5)?" <a class='btn btn-danger' role='button' href='javascript:studentRemove(" . $row['studentID'] . ",\"" . $row['first']." ".$row['last'] . "\")'><span class='bi bi-eraser'></span> Remove</a>":"";
		$output .= "</div>";
		//show privilege
		if(userHasPrivilege(3))
		{
			if(empty($row['userID']))
			{
					$output .= "User has never logged in with registered account.";
			}
			else {
				$query = "SELECT * FROM `user` WHERE `userID`=".$row['userID'];// where `field` = $fieldId";
				//$output .= $query;
				//TODO: Add last logged in to db and print it here
				$resultPrivilege = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
		$output .="<div>Grade: ".getStudentGrade($row['yearGraduating'])." (".$row['yearGraduating'].")</div>";
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
			$output .="<div>Phone(".getPhoneString($row['phoneType'])."): ".$row['phone']."</div>";
		}
		if(userHasPrivilege(3))
		{
			$output .="<div><a class='btn btn-primary' role='button' href='#student-details-".$row['studentID']."'><span class='bi bi-journal'></span> Details</a></div>";
		}

		$output .="</div>";
	endwhile; // end loop through student list
	//complete enclosing div
}
echo $output;
?>
