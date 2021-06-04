<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
require_once  ("functions.php");
//text output
$output = "";

$year = intval($_POST['year']);

/*check to see if id exists*/
$query = "SELECT * from `event`";// where `field` = $fieldId";

if($year)
{
	$yearQuery = "SELECT `eventID` FROM `eventyear` WHERE `year` = $year";
	// echo $yearQuery;
	$resultYear1 = $mysqlConn->query($yearQuery) or print("\n<br />Warning: query failed: $query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	$eventIDs = "";
	while ($row = $resultYear1->fetch_assoc()):
		//make array of results
		$eventIDs .= $eventIDs!=""?", ":"";
		$eventIDs .= "'".$row['eventID']."'";
	endwhile;
	// echo $eventNames;

	if($eventIDs  !="")
	{
		$query.=" where `event`.`eventID` IN ($eventIDs)";
	}
	else {
		echo "There are no events in the year $year.";
		return 0;
	}
}

$query.=" ORDER BY `event`";
if(userHasPrivilege(3))
{
	echo $query ;
}
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$output .="<hr><h2>".$row['event']."</h2>";
		$output .="";

		//check for permissions to create edit an event btn
		if(userHasPrivilege(3) )
		{
			$output .="<input class='button fa' type='button' onclick='window.location.hash=\"event-edit-".$row['eventID']."\"' value='&#xf108; Edit' />";
		}


		$query = "SELECT * from `eventyear` LEFT JOIN `student` ON `eventyear`.`studentID` = `student`.`studentID`  WHERE `eventyear`.`eventID` = '".($row['eventID'])."' ORDER BY `eventyear`.`year` ASC";
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
