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
			$output .="<div><a class='btn btn-primary' role='button' href='javascript:coachEdit(".$row['coachID'].")'><span class='fa fa-edit'></span> Edit</a></div>";
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
$query = "SELECT * FROM `officer` INNER JOIN `student` ON `officer`.`studentID`= `student`.`studentID` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'] . " AND `year`=$year";
//$output .=$query;
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<br><hr><h2>Officers May $yearBeg - $year</h2>";
	$output .='<div>';
	if(userHasPrivilege(3))
	{
		$output .="<a class='btn btn-primary' role='button' href='#officer-emails-$year'><span class='fa'>&#xf01c;</span> Get Officer Emails</a>";
	}
	if(userHasPrivilege(4))
	{
		$output .=" <a class='btn btn-primary' role='button' href='#officer-add-$year'><span class='fa fa-plus'></span> Add Officer</a>";
	}
		$output .='</div><div>';
	while ($row = $result->fetch_assoc()):
		$output .="<div id='officer-".$row['officerID']."'>";
		$officerName = $row['first']." ".$row['last'];
		$output .="<hr><h3>$officerName</h3>";
		if($row['position'])
		{
			$output .="<h4>".$row['position']."</h4>";
		}
		if(userHasPrivilege(5))
		{
			$output .="<a class='btn btn-warning' role='button' href='javascript:officerRemove(\"".$row['officerID']."\",\"$officerName\")''><span class='fa fa-eraser'></span> Remove</a>";
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

$query = "SELECT * FROM `eventyear` INNER JOIN `student` ON `eventyear`.`studentID`= `student`.`studentID` INNER JOIN `event` ON `eventyear`.`eventID`=`event`.`eventID` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'] . " AND `year`=$year";
//$output .=$query;
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<br><hr><div>";
	$output .="<h2>Event Leaders May $yearBeg - $year</h2>";
	if(userHasPrivilege(4))
	{
		$output .="<div style='color:blue'>Note to coach: Modify event leaders in events.</div>";
	}
	if(userHasPrivilege(2))
	{
		$output .="<a class='btn btn-primary' role='button' href='#eventleader-emails-$year'><span class='fa'>&#xf01c;</span> Get Event Leader Emails</a>";
	}
	while ($row = $result->fetch_assoc()):
		$output .="<div id='eventleader-".$row['eventyearID']."'>";
		$officerName = $row['first']." ".$row['last'];
		$output .="<hr><h3>$officerName</h3>";
		if($row['event'])
		{
			$output .="<h4>".$row['event']."</h4>";
		}
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
