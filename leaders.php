<?php
require_once  ("php/functions.php");
userCheckPrivilege(1);

$year = isset($_POST['myID'])?intval($_POST['myID']):getCurrentSOYear();

//text output
$output = "<div>" . getSOYears($year) . "</div>";
$output .= "<br></br><h2>Coaches</h2><div>";

$query = "SELECT * FROM `coach` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'];
//$output .=$query;
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	while ($row = $result->fetch_assoc()):
		$output .="<div>";
		$output .="<hr><h2>".$row['first']." ".$row['last']."</h2>";
		if(userHasPrivilege(5) || $_SESSION['userData']['userID']==$row['userID'])
		{
			$output .="<div><a class='btn btn-primary' role='button' href='javascript:coachEdit(".$row['coachID'].")'><span class='bi bi-pencil-square'></span> Edit</a></div>";
		}
		if($row['position'])
		{
			$output .="<div>".$row['position']."</div>";
		}
		if($row['emailSchool'])
		{
			$output .="<div>Email: <a href='mailto: ".$row['emailSchool']."'>".$row['emailSchool']."</a></div>";
		}
	endwhile;
	$output .="</div>";
}

//Get current year
$yearBeg = $year-1;
$query = "SELECT * FROM `officer` INNER JOIN `student` ON `officer`.`studentID`= `student`.`studentID` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'] . " AND `year`=$year ORDER BY `officerID`";
//$output .=$query;
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<br><hr><h2>Officers May $yearBeg - $year</h2>";
	$output .='<div>';
	if(userHasPrivilege(3))
	{
		$output .="<a class='btn btn-primary' role='button' href='#officer-emails-$year'><span class='bi bi-envelope'></span> Get Officer Emails</a>";
	}
	if(userHasPrivilege(4))
	{
		$output .=" <a class='btn btn-primary' role='button' href='#officer-addform-$year'><span class='bi bi-plus-circle'></span> Add Officer</a>";
	}
		$output .='</div><div>';
	while ($row = $result->fetch_assoc()):
		$output .="<hr><div id='officer-".$row['officerID']."'>";
		$output .="<h3>".$row['position']."</h3>";
		$leaderName = $row['first']." ".$row['last'];
		$output .="<h4>$leaderName</h4>";
		if(userHasPrivilege(5))
		{
			$output .="<a class='btn btn-warning' role='button' href='javascript:officerRemove(\"".$row['officerID']."\",\"$leaderName\")''><span class='bi bi-eraser'></span> Remove</a>";
		}
		$output .="<div>Grade: ".getStudentGrade($row['yearGraduating'])." (".$row['yearGraduating'].")</div>";
		if($row['email'])
		{
			$output .="<div>Google Email:".$row['email']."</div>";
		}
		if($row['emailSchool'])
		{
			$output .="<div>School Email:".$row['emailSchool']."</div>";
		}
		if($row['phone'])
		{
			$output .="<div>Phone(".getPhoneString($row['phoneType'])."):".$row['phone']."</div>";
		}
		$output .="</div>";
	endwhile;
	$output .="</div>";
}

$query = "SELECT * FROM `eventleader` INNER JOIN `student` ON `eventleader`.`studentID`= `student`.`studentID` INNER JOIN `event` ON `eventleader`.`eventID`=`event`.`eventID` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'] . " AND `year`=$year ORDER BY `event`";
//$output .=$query;
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<br><hr><div>";
	$output .="<h2>Event Leaders May $yearBeg - $year</h2>";
	if(userHasPrivilege(2))
	{
		$output .="<a class='btn btn-primary' role='button' href='#eventleader-emails-$year'><span class='bi bi-envelope'></span> Get Event Leader Emails</a>";
	}
	if(userHasPrivilege(4))
	{
		$output .=" <a class='btn btn-primary' role='button' href='#eventleader-addform-$year'><span class='bi bi-plus-circle'></span> Add Event Leader</a>";
	}
	while ($row = $result->fetch_assoc()):
		$output .="<hr><div id='eventleader-".$row['eventleaderID']."'>";
		$output .="<h3>".$row['event']."</h3>";
		$leaderName = $row['first']." ".$row['last'];
		$output .="<h4>$leaderName</h4>";

		if(userHasPrivilege(5))
		{
			$output .="<a class='btn btn-warning' role='button' href='javascript:officerRemove(\"".$row['eventleaderID']."\",\"$leaderName\")''><span class='bi bi-eraser'></span> Remove</a>";
		}
		$output .="<div>Grade: ".getStudentGrade($row['yearGraduating'])." (".$row['yearGraduating'].")</div>";
		if($row['email'])
		{
			$output .="<div>Google Email:".$row['email']."</div>";
		}
		if($row['emailSchool'])
		{
			$output .="<div>School Email:".$row['emailSchool']."</div>";
		}
		if($row['phone'])
		{
			$output .="<div>Phone(".getPhoneString($row['phoneType'])."):".$row['phone']."</div>";
		}
		$output .="</div>";
	endwhile;
	$output .="</div>";
}

echo $output;
?>
