<?php
require_once("php/functions.php");
userCheckPrivilege(1);
$myStudentID = getStudentID($_SESSION['userData']['userID']);
$studentID = isset($_REQUEST['myID'])?intval($_REQUEST['myID']):0;
$year = getCurrentSOYear();
$query = "SELECT * FROM `student` WHERE `studentID` = $studentID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if($result)
{
	$row = $result->fetch_assoc();

$output ="<div id='student-$studentID'>";
$output .="<h2>".$row['last'] . ", " . $row['first']."</h2>";
$officerPos = getOfficerPosition($studentID);
if($officerPos)
{
	$output .="<h3>Officer: $officerPos</h3>";
}

$eventLeaderPos = getEventsText(getEventLeaderPosition($row['studentID'],$year));
if($eventLeaderPos)
{
	$output .="<h3>Leading Event(s): $eventLeaderPos</h3>";
}
if(userHasPrivilege(4))
{
	$output.="<div><a class='btn btn-warning' role='button' href='#student-edit-$studentID'><span class='bi bi-pencil-square'></span> Edit</a>";
}
if(userHasPrivilege(4))
{
	$output.=" <a class='btn btn-dark' role='button' href='impersonate.php?userID=".$row['userID']."'><span class='bi bi-file-earmark-person'></span> Impersonate User</a>";
}
if(userHasPrivilege(5))
{
	$output.=" <a class='btn btn-danger btn-sm' role='button' href='javascript:studentRemove($studentID,'".$row['last'] . ", " . $row['first']."')'><span class='bi bi-eraser'></span> Remove</a>";
}
if(userHasPrivilege(3))
{
	$output .= "</div>";
	if(empty($row['userID']))
	{
			$output .= "User has never logged in with registered account.";
	}
	else {
		$query = "SELECT * FROM `user` WHERE `userID`=".$row['userID'];// where `field` = $fieldId";
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
if(userHasPrivilege(4)||$studentID==$myStudentID)
{
	$output .= "<div>School Name: ".getCurrentSchoolName($row['schoolID'])."</div>";
	$output .= "<div>Student's School ID: ".$row['studentschoolID']."</div>";
	$output .= "<div>Scilympiad ID: ".$row['scilympiadID']."</div>";
}
$output .="<div>".getStudentGradeGraduate($row['yearGraduating'])."</div>";
if($row['email'])
{
	$output .="<div>Google Email: <a href='mailto:".$row['email']."'>".$row['email']."</a></div>";
}
if($row['emailSchool'])
{
	$output .="<div>School Email: <a href='mailto:".$row['emailSchool']."'>".$row['emailSchool']."</a></div>";
}
if($row['phone'])
{
	$output .="<div>Phone(".getPhoneString($row['phoneType'])."): ".$row['phone']." <a class='btn btn-secondary btn-sm' role='button' href='tel:".$row['phone']."'><span class='bi bi-telephone'> Call</span></a></div>";
}


if(userHasPrivilege(3)||$studentID==$myStudentID)
{
	$officerPosPrev = getOfficerPositionPrevious($studentID);
	if($officerPosPrev)
	{
		$output .="<div>Previous Positions: $officerPosPrev</div>";
	}
	$eventLeaderPosPrev = getEventLeaderPositionPrevious($studentID, $year);
	if($eventLeaderPosPrev)
	{
		$output .="<div>Previous Event(s) Lead: $eventLeaderPosPrev</div>";
	}

	if($row['parent1Last'])
	{
		$output .="<br><h3>Parent(s)</h3>";
		$output .="<div>".$row['parent1First']." ".$row['parent1Last'].", ".$row['parent1Email'].", ".$row['parent1Phone']." <a class='btn btn-secondary btn-sm' role='button' href='tel:".$row['parent1Phone']."'><span class='bi bi-telephone'> Call</span></a></div>";
		if($row['parent2Last'])
		{
			$output .="<div>".$row['parent2First']." ".$row['parent2Last'].", ".$row['parent2Email'].", ".$row['parent2Phone']." <a class='btn btn-secondary btn-sm' role='button' href='tel:".$row['parent2Phone']."'><span class='bi bi-telephone'> Call</span></a></div>";
		}
	}
	$output .= "<hr>";
	//Get latest team assignments
	$teamRow = getLatestTeamTournamentStudentRow($studentID);
	$output .=printLatestTeamTournamentStudent($studentID, $teamRow);
	$output .=studentEventPriority($studentID);
	$output .=studentCourseCompleted($studentID);
	$output .=studentCourseEnrolled($studentID);
	$output .=studentAwards($studentID);
	$output .=studentTournamentResultsList($studentID, true);
}


$output .="</div>";
}
echo $output;
?>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>
