
<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");
//TODO: Add year choice to look at previous years
//Get current year
$year = getCurrentSOYear();
$yearBeg = $year-1;
$query = "SELECT * FROM `officer` t1 INNER JOIN `student` t2 ON t1.`studentID`=t2.`studentID` WHERE `year`=$year";
//$output .=$query;
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
	$output .='<input class="button fa" type="button" onclick="javascript:needtoadd()" value="&#xf002; Find" />';
	if($_SESSION['userData']['privilege']>3 )
	{
		$output .=' <input class="button fa" type="button" onclick="javascript:prepareOfficerAdd()" value="&#xf055; Add Officer" />';
	}
	$output .='<br><br>';
	$output .="<h2>Officers May $yearBeg - $year</h2>";
	while ($row = $result->fetch_assoc()):
		$output .="<div id='officer-".$row['officerID']."'>";
		$output .="<hr><h3>".$row['first']." ".$row['last']."</h3>";
		if($row['position'])
		{
			$output .="<h4>".$row['position']."</h4>";
		}
		if($_SESSION['userData']['privilege']>3)
		{
			$output .="<div><a href='javascript:officerRemove(\"".$row['officerID']."\")'>Remove</a></div>";
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
			$output .="<div>Phone(".$row['phoneType']."):".$row['phone']."</div>";
		}
		$output .="</div>";
	endwhile;
	$output .="</div>";
}

$query = "SELECT * FROM `eventyear` t1 INNER JOIN `student` t2 ON t1.`studentID`=t2.`studentID` WHERE `year`=$year";
//$output .=$query;
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<hr><hr><div>";
	$output .="<h2>Event Leaders May $yearBeg - $year</h2>";
	while ($row = $result->fetch_assoc()):
		$output .="<div id='eventleader-".$row['eventyear']."'>";
		$output .="<hr><h3>".$row['first']." ".$row['last']."</h3>";
		if($row['event'])
		{
			$output .="<h4>".$row['event']."</h4>";
		}
		if($_SESSION['userData']['privilege']>3)
		{
			$output .="<div><a href='javascript:officerRemove(\"".$row['officerID']."\")'>Remove</a></div>";
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
			$output .="<div>Phone(".$row['phoneType']."):".$row['phone']."</div>";
		}
		$output .="</div>";
	endwhile;
	$output .="</div>";
}

echo $output;
?>
