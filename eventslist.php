<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");
//text output
$output = "";

$year = intval($_POST['year']);

/*check to see if id exists*/
$query = "SELECT * from `event`";// where `field` = $fieldId";

if($year)
{
	$yearQuery = "SELECT `event` FROM `eventyear` WHERE `year` = $year";
	// echo $yearQuery;
	$resultYear1 = $mysqlConn->query($yearQuery) or print("\n<br />Warning: query failed: $query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	$eventNames = "";
	while ($row = $resultYear1->fetch_assoc()):
		//make array of results
		$eventNames .= $eventNames!=""?", ":"";
		$eventNames .= "'".$row['event']."'";
	endwhile;
	// echo $eventNames;

	if($eventNames !="")
	{
		$query.=" where `event`.`event` IN ($eventNames)";
	}
	else {
		echo "There are no events in the year $year.";
		return 0;
	}
}


$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$output .="<hr><h2>".$row['event']."</h2>";
		$output .="";

		//check for permissions to create edit an event btn
		if($_SESSION['userData']['privilege']>2 )
		{
			$output .="<a href='javascript:prepareEventsEditPage(\"".$mysqlConn->real_escape_string($row['event']). "\")'>Edit</a>";
		}


		$query = "SELECT * from `eventyear` LEFT JOIN `student` ON `eventyear`.`studentID` = `student`.`studentID`  WHERE `eventyear`.`event` = '".$mysqlConn->real_escape_string($row['event'])."' ORDER BY `eventyear`.`year` ASC";// where `field` = $fieldId";
		$resultYear2 = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

		$yearCollection = "";
		$selectYear = $year?$year:getCurrentSOYear();
		while ($rowYear = $resultYear2->fetch_assoc()):
			if($rowYear['year']==$selectYear && $rowYear['studentID'])
			{
				//print Event Leader
				$output .="<div>Event Leader: ".$rowYear['first']." ".$rowYear['last']."</div>";
			}
			$yearCollection .= $yearCollection!=""?", ":"";
			$yearCollection .= $rowYear['year'];
		endwhile;


		$output .="<div>Year: $grade ".$yearCollection."</div>";
    $output .="<div>Type: ".$row['type']."</div>";

	endwhile;
  $output .="</div>";
}

echo $output;
?>
