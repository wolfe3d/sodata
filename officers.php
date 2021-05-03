
<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
//TODO: Add year choice to look at previous years
//Get current year
$year = date("m")>4 ? date("Y")+1 : date("Y");
$yearBeg = $year-1;
$query = "SELECT * FROM `officer` t1 INNER JOIN `student` t2 ON t1.`studentID`=t2.`studentID` WHERE `year`=$year";
//$output .=$query;
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output ="<div>";
	$output .="<div>Officers from May $yearBeg - $year</div>";
	while ($row = $result->fetch_assoc()):
		$output .="<div>";
		$output .="<hr><h2>".$row['first']." ".$row['last']."</h2>";
		if($row['position'])
		{
			$output .="<h3>".$row['position']."</h3>";
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
