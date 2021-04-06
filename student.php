<?php
require_once ("../connectsodb.php");
//text output
$output = "";

$last = $mysqlConn->real_escape_string($_REQUEST['last']);
$first = $mysqlConn->real_escape_string($_REQUEST['first']);
$query = "SELECT * from `students` where ";
//check to see what is searched for
if($last&&$first)
{
	$query .= "`last` LIKE '$last' AND `first` LIKE '$first' ORDER BY `last` ASC";
}
else if($last)
{
	$query .= "`last` LIKE '$last' ORDER BY `last` ASC";
}
else if($first)
{
	$query .= "`first` LIKE '$first' ORDER BY `first` ASC";
}
else
{
	return "Failed";
}

$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$output .="<div>";
if($result)
{
	while ($row = $result->fetch_assoc()):
		$output .="<hr><h2>".$row['first']." ".$row['last']."</h2>";
		$output .="<div><a href='studentedit.php?studentID=".$row['studentID']."'>Edit</a></div>";
		$grade = 9;
		if (date("M")>5)
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
			$output .="<div>Preferred Email:".$row['email']."</div>";
		}
		if($row['emailAlt'])
		{
			$output .="<div>Alternate Email:".$row['emailAlt']."</div>";
		}
		if($row['phone'])
		{
			$output .="<div>Phone(".$row['phoneType']."):".$row['phone']."</div>";
		}	
		if($row['parent1Last'])
		{
			$output .="<h3>Parent(s)</h3>";
			$output .="<div>".$row['parent1First']." ".$row['parent1Last'].",".$row['parent1Email'].",".$row['parent1Phone']."</div>";
			if($row['parent2Last'])
			{
				$output .="<div>".$row['parent2First']." ".$row['parent2Last'].",".$row['parent2Email'].",".$row['parent2Phone']."</div>";
			}
		}
	endwhile;
}
else
{
	$output .="No Results";
}
$output .="</div>";
echo $output;
?>