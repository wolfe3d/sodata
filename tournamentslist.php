<?php
require_once ("../connectsodb.php");
//text output
$output = "";


$name = $mysqlConn->real_escape_string($_POST['tournamentName']);
$year = intval($_POST['tournamentYear']);

$query = "SELECT * from `tournaments` t1";
//check to see what is searched for
if($last&&$first)
{
	$query .= " where t1.`last` LIKE '$last' AND t1.`first` LIKE '$first'";
}
else if($last)
{
	$query .= " where t1.`last` LIKE '$last'";
}
else if($first)
{
	$query .= " where t1.`first` LIKE '$first'";
}

$query .= " ORDER BY t2.`tournamentName` ASC";
$output .=$query;
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
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
		//find student's events
		$query = "SELECT * FROM `eventschoice` t1 INNER JOIN `eventsyear` t2 ON t1.`eventID`=t2.`eventID` WHERE `studentID`=".$row['studentID'];// where `field` = $fieldId";
		$resultEventsChoice = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if (mysqli_num_rows($resultEventsChoice)>0)
		{
				$output .="<h3>Events</h3>";
				while ($rowEventsChoice = $resultEventsChoice->fetch_assoc()):
					$output .= "<div id='eventChoice-" . $rowEventsChoice['eventsChoiceID'] . "'>" . $rowEventsChoice['year'] . " " . $rowEventsChoice['event'] . "</div>";
				endwhile;
		}

	$output .="</div>";
}
echo $output;
?>
