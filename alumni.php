<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(3);

$year = getCurrentSOYear();
$studentID = getStudentID($_SESSION['userData']['userID']);
$studentIDWhere = "";
if($studentID)
{
	$studentIDWhere ="AND `student`.`studentID` != $studentID";
}


$query = "SELECT `student`.`studentID`, `student`.`first`, `student`.`last`, `student`.`yearGraduating`, `student`.`schoolID`, `student`.`phoneType`, `student`.`phone`, `student`.`email`
FROM `student`
WHERE `student`.`yearGraduating` < $year AND `student`.`schoolID` = $schoolID
ORDER BY `student`.`yearGraduating`, `student`.`last`, `student`.`first`";
$result = $mysqlConn->query($query);

// Get alumni emails button
$output = "<a class='btn btn-secondary' role='button' href='#alumni-emails'><span class='bi bi-envelope'></span> Get Alumni Emails</a>";
$output .= "<p id='query'>$query</p>";
$output .= "<div id='list'></div>";

while ($row = $result->fetch_assoc()) {
    $output .="<div id='student-". $row['studentID'] ."'>";
    $output .="<hr><h2>".$row['first']." ".$row['last']." (".$row['yearGraduating'].")"."</h2>";
    $output .="<div>";
    if(userHasPrivilege(3))
    {
        $output .="<a class='btn btn-primary' role='button' href='#student-details-".$row['studentID']."'><span class='bi bi-journal'></span> Details</a> ";
    }
    if(userHasPrivilege(4)) //||$_SESSION['userData']['id']==$row['userID']) //Users cannot edit their own information
    {
        $output .="<a class='btn btn-warning' role='button' href='#student-edit-".$row['studentID']."'><span class='bi bi-pencil-square'></span> Edit</a>";
    }
    $output .= userHasPrivilege(5)?" <a class='btn btn-danger btn-sm' role='button' href='javascript:studentRemove(" . $row['studentID'] . ",\"" . $row['first']." ".$row['last'] . "\")'><span class='bi bi-eraser'></span> Remove</a>":"";
    $output .= "</div>";
    $officerPos = getOfficerPositionPrevious($row['studentID']);
    if($officerPos)
    {
        $output .="<h4>Officer: $officerPos</h4>";
    }
    $eventLeaderPos = getEventLeaderPositionPrevious($row['studentID'],$row['yearGraduating']);
    if($eventLeaderPos)
    {
        $output .="<h4>Led Event(s): $eventLeaderPos</h4>";
    }
    $output .="<div>Grade: ".getStudentGrade($row['yearGraduating'])."</div>";
	if($row['email'])
	{
		$output .="<div>Google Email: <a href='mailto:".$row['email']."'>".$row['email']."</a></div>";
	}
	if($row['phone'])
	{
		$output .="<div>Phone(".getPhoneString($row['phoneType'])."): ".$row['phone']." <a class='btn btn-secondary btn-sm' role='button' href='tel:".$row['phone']."'><span class='bi bi-telephone'> Call</span></a></div>";
	}
    $output .="</div>";
}
echo $output;
?>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>
