
<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
require_once  ("functions.php");
$schoolID = 1; //TODO: Change this to user found $schoolID

$year = isset($_POST['myID'])?intval($_POST['myID']):getCurrentSOYear();

//text output
$output = "<div>" . getSOYears($year) . "</div>";
$output .= "<br></br><h2>Coaches</h2><div>";

$query = "SELECT * FROM `coach` WHERE `schoolID`=$schoolID";
//$output .=$query;
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	while ($row = $result->fetch_assoc()):
		$output .="<div>";
		$output .="<hr><h2>".$row['first']." ".$row['last']."</h2>";
		if($_SESSION['userData']['privilege']>3 || $_SESSION['userData']['id']==$row['userID'])
		{
			$output .="<div><a href='javascript:coachEdit(".$row['coachID'].")'>Edit</a> ";
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
$query = "SELECT * FROM `officer` INNER JOIN `student` ON `officer`.`studentID`= `student`.`studentID` WHERE `year`=$year AND `schoolID` = $schoolID";
//$output .=$query;
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
		if(userHasPrivilege(3))
	{
		$output .="<br><input class='button fa' type='button' onclick=location.href='#officer-emails-$year' value='&#xf01c; Get Officer Emails' />";
	}
	if(userHasPrivilege(4))
	{
		$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"officer-add-".$year."\"' value='&#xf067; Add Officer' />";
	}

	$output .='<br><br>';
	$output .="<h2>Officers May $yearBeg - $year</h2>";
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
			$output .="<div><a href='javascript:officerRemove(\"".$row['officerID']."\",\"$officerName\")'>Remove</a></div>";
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

$query = "SELECT * FROM `eventyear` INNER JOIN `student` ON `eventyear`.`studentID`= `student`.`studentID` INNER JOIN `event` ON `eventyear`.`eventID`=`event`.`eventID` WHERE `year`=$year AND `schoolID` = $schoolID";
//$output .=$query;
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<hr><hr><div>";
	$output .="<h2>Event Leaders May $yearBeg - $year</h2>";
	if(userHasPrivilege(4))
	{
		$output .="<div style='color:blue'>Note to coach: Modify event leaders in events.</div>";
	}
	if(userHasPrivilege(2))
	{
		$output .="<input class='button fa' type='button' onclick=location.href='#eventleader-emails-$year' value='&#xf01c; Get Event Leader Emails' />";
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