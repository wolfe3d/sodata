<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
//text output
$output = "";

$year = intval($_POST['year']);

/*check to see if id exists*/
$query = "SELECT * from `events`";// where `field` = $fieldId";


if($year)
{
	$yearQuery = "SELECT `event` FROM `eventsyear` WHERE `year` LIKE $year";
	// echo $yearQuery;
	$result = $mysqlConn->query($yearQuery) or print("\n<br />Warning: query failed: $query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	$eventNames = "";
	while ($row = $result->fetch_assoc()):
		//make array of results

		if($eventNames !="")
		{
			$eventNames .=",";
		}
		$eventNames .= "'".$row['event']."'";
	endwhile;

	if($eventNames !="")
	{
		$query.=" where `events`.`event` IN ($eventNames)";
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

		$query = "SELECT * from `eventsyear` WHERE eventsyear.`event` = '".$row['event']."' ORDER BY `eventsyear`.`year` ASC";// where `field` = $fieldId";
		$resultYear = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

		$yearCollection = "";
		while ($rowYear = $resultYear->fetch_assoc()):
			if($yearCollection){
				$yearCollection.=", ";
			}
			$yearCollection .= $rowYear['year'];
		endwhile;


		$output .="<div>Year: $grade ".$yearCollection."</div>";
    $output .="<div>Type: ".$row['type']."</div>";

	endwhile;
  $output .="</div>";
}



echo $output;
?>
