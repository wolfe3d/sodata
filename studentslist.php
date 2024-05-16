<?php
require_once  ("php/functions.php");
userCheckPrivilege(1);

//text output
$output = "";
$search = isset($_POST['search'])?$mysqlConn->real_escape_string($_POST['search']):0;
$active = isset($_POST['active'])?intval($_POST['active']):0;

$year = getCurrentSOYear();

$query = "";
//check to see what is searched for
$query="SELECT DISTINCT `student`.`studentID` FROM `student`";
if($search)
{
$query .= " INNER JOIN `eventchoice` ON `student`.`studentID`=`eventchoice`.`studentID` 
INNER JOIN `eventyear` ON `eventchoice`.`eventyearID`=`eventyear`.`eventyearID`
INNER JOIN `event` AS `eventChosen` ON `eventyear`.`eventID`=`eventChosen`.`eventID`

INNER JOIN `teammateplace` ON `student`.`studentID`=`teammateplace`.`studentID`
INNER JOIN `tournamentevent` ON `tournamentevent`.`tournamenteventID`=`teammateplace`.`tournamenteventID`
INNER JOIN `event` AS `eventCompeted` ON `tournamentevent`.`eventID`=`eventCompeted`.`eventID`

INNER JOIN `coursecompleted` ON `student`.`studentID`=`coursecompleted`.`studentID`
INNER JOIN `course` AS `courseTableCompleted` ON `coursecompleted`.`courseID` = `courseTableCompleted`.`courseID`

INNER JOIN `courseenrolled` ON `student`.`studentID`=`courseenrolled`.`studentID` 
INNER JOIN `course` AS `courseTableEnrolled` ON `courseenrolled`.`courseID` = `courseTableEnrolled`.`courseID`

WHERE `schoolID` = " . $_SESSION['userData']['schoolID'] .
" AND 
    (`student`.`last` LIKE '%$search%'
    OR `student`.`first` LIKE '%$search%'
    OR `student`.`yearGraduating` LIKE '%$search%'
    OR `student`.`email` LIKE '%$search%'
    OR `student`.`emailSchool` LIKE '%$search%'
    OR `student`.`parent1Last` LIKE '%$search%'
    OR `student`.`parent1First` LIKE '%$search%'
    OR `student`.`parent1Email` LIKE '%$search%'
    OR `student`.`parent1Last` LIKE '%$search%'
    OR `student`.`parent1First` LIKE '%$search%'
    OR `student`.`parent1Email` LIKE '%$search%'
    OR `eventChosen`.`event` LIKE '%$search%'
	OR `eventCompeted`.`event` LIKE '%$search%'
    OR `courseTableCompleted`.`course` LIKE '%$search%'
    OR `courseTableEnrolled`.`course` LIKE '%$search%'
    )
";
}
else
{
	$query.=" WHERE `student`.`schoolID` = " . $_SESSION['userData']['schoolID'];
}

if($active)
{
	$query .= " AND `student`.`active` = 1";
}

$output .= userHasPrivilege(3)?"<br>" . $query . "<br>":"";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$studentIDs ="";
if($result)
{
	while ($row = $result->fetch_assoc()):
		//make array of results
		if($studentIDs !="")
		{
			$studentIDs .=",";
		}
		$studentIDs .= $row['studentID'];
	endwhile;
}

$query ="SELECT * FROM `student` WHERE `student`.`studentID` IN ($studentIDs) ORDER BY `student`.`last`, `student`.`first`";
$output .= userHasPrivilege(3)?"<br>" . $query . "<br>":"";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	while ($row = $result->fetch_assoc()):
		$output .="<div id='student-". $row['studentID'] ."'>";
		$output .="<hr><h2>".$row['first']." ".$row['last']."</h2>";
		$output .="<div>";
		if(userHasPrivilege(3))
		{
			$output .="<a class='btn btn-primary' role='button' href='#student-details-".$row['studentID']."'><span class='bi bi-journal'></span> Details</a> ";
		}
		$output .=userHasPrivilege(4)?"<a class='btn btn-warning' role='button' href='#student-edit-".$row['studentID']."'><span class='bi bi-pencil-square'></span> Edit</a>":"";
		$output .= userHasPrivilege(5)?" <a class='btn btn-danger btn-sm' role='button' href='javascript:studentRemove(" . $row['studentID'] . ",\"" . $row['first']." ".$row['last'] . "\")'><span class='bi bi-eraser'></span> Remove</a>":"";
		$output .= "</div>";
		$officerPos = getOfficerPosition($mysqlConn,$row['studentID']);
		if($officerPos)
		{
			$output .="<h4>Officer: $officerPos</h4>";
		}
		$eventLeaderPos = getEventLeaderPosition($mysqlConn,$row['studentID'],$year);
		if($eventLeaderPos)
		{
			$output .="<h4>Leading Event(s): $eventLeaderPos</h4>";
		}

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
			$output .="<div>Phone(".getPhoneString($row['phoneType'])."): ".$row['phone']." <a class='btn btn-secondary btn-sm' role='button' href='tel:".$row['phone']."'><span class='bi bi-telephone'> Call</span></a></div>";
		}

		$output .="</div>";
	endwhile; // end loop through student list
	//complete enclosing div
}
echo $output;
?>
