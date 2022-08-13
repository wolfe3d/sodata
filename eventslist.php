<?php
require_once  ("php/functions.php");
userCheckPrivilege(1);

function getEventYears($db, $eventID)
{
	$query = "SELECT * FROM `eventyear` WHERE `eventID`=$eventID ORDER BY `divisionID` AND `year`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && $result->num_rows>0){
		$output = "<div>";
		$years = "";
		$division = "";
		while ($row = $result->fetch_assoc()):
			if($division!=$row['divisionID'])
			{
				if($division)
				{
					$output .= "$years</span> ";
				}
				$output .= "<span>Division ".$row['divisionID'].": ";
				$years = "";
			}
			if($years)
			{
				$years .= ", ";
			}
			$division = $row['divisionID'];
			$years.=$row['year'];
		endwhile;
		$output .= "$years</span></div>";
		return $output;
	}
	return "Trial Event";
}


//Get the current event Leader for this school of this event during the selected year
function getEventLeader($db, $eventID, $year, $schoolID)
{
	$yearWhere = "";
	if($year)
	{
		$yearWhere = "AND `eventleader`.`year` = $year";
	}
	$query = "SELECT `student`.`studentID`, `first`, `last`, `year` from `eventleader` INNER JOIN `student` ON `eventleader`.`studentID` = `student`.`studentID`  WHERE `schoolID` = $schoolID AND `eventleader`.`eventID` = $eventID $yearWhere";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = "";
	$leaderNumber = 0;
	if($result && $result->num_rows>0){
		$output = "<div>Event Leader: ";
		while ($row = $result->fetch_assoc()):
			if($leaderNumber)
			{
				$output .= ", ";
			}
			$yearString = "";
			if(!$year)
			{
				$yearString = "(" . $row['year'] . ")";
			}
			$output .= "<a href='#student-details-". $row['studentID'] ."'>".$row['first']." ".$row['last']." $yearString</a>";
			$leaderNumber +=1;
		endwhile;
	}
	else
	{
		//Add leader
		$output .=" <a class='btn btn-primary' role='button' href='#eventleader-addform-$year-event=$eventID' data-toggle='tooltip' data-placement='top' title='Add Event Leader'><span class='bi bi-plus-circle'></span> Add Leader</a>";
	}
	return $output;
}

$output = "";//text output
/*check to see if year exists
If so, use the year sent by the choice box
If not, use the year by the id events-# or the current year*/
$year = isset($_POST["year"])?intval($_POST['year']):getCurrentSOYear();

$yearWhere = ""; //the year is  0, so show all years
if($year) //if another year is set narrow search
{
	$yearWhere = "AND `year` = '$year'";
}

$divisionID = isset($_POST["division"])?$mysqlConn->real_escape_string($_POST['division']):getCurrentSchoolDivision($mysqlConn);
$divisionWhere = ""; //the division is  0, so show all divisions
if($divisionID) //if another division is set narrow search
{
	$divisionWhere = "AND `divisionID`= '$divisionID'";
}

//show all EventS filtered by year and division
//in each show the division and years.
$query = "SELECT DISTINCT `event`.`eventID`, `event`, `type`, `goggleType`, `numberStudents`, `calculatorType`, `sciolyLink`, `description` FROM `event` LEFT JOIN `eventyear` ON `event`.`eventID`=`eventyear`.`eventID` WHERE 1 $yearWhere $divisionWhere ORDER BY `event`";

if(userHasPrivilege(4))
{
	echo $query ;
}
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed: $query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result&& $result->num_rows>0)
{
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$output .="<hr><h2>".$row['event']."</h2>";
		$output .="";

		//check for permissions to create edit an event btn
		$output .="<div class='btn-group' role='group' aria-label='Event Buttons'>"; //start button group
		if(userHasPrivilege(3) )
		{
			$output .="<a class='btn btn-primary' role='button' href='#event-edit-".$row['eventID']."' data-toggle='tooltip' data-placement='top' title='Edit'><span class='bi bi-pencil-square'></span></a>";
		}
		if(userHasPrivilege(2))
		{
			$output .=" <a class='btn btn-primary' role='button' href='#event-emails-".$row['eventID']."' data-toggle='tooltip' data-placement='top' title='Get emails'><span class='bi bi-envelope'></span></a>";
		}
		if(userHasPrivilege(3) )
		{
			$output .=" <a class='btn btn-primary' role='button' href='#event-analysis-".$row['eventID']."' data-toggle='tooltip' data-placement='top' title='Analysis'><span class='bi bi-pie-chart-fill'></span></a>";
		}
		$output .="</div>"; //end button group

		//$yearCollection = $yearCollection?$yearCollection:"Trial Event";
		$output .=getEventLeader($mysqlConn, $row['eventID'], $year, $_SESSION['userData']['schoolID'] );
		$output .=getEventYears($mysqlConn, $row['eventID']);
		$output .="<div>Type: ".getEventString($row['type'])."</div>";
		if($row['calculatorType']){
			$output .="<div>Calculator: ".getCalulatorString($row['calculatorType'])."</div>";
		}
		if($row['goggleType']){
			$output .="<div>Goggles: ".getGoggleString($row['goggleType'])."</div>";
		}
		if($row['numberStudents']){
			$output .="<div>Number of partners: ".$row['numberStudents']."</div>";
		}
		if($row['sciolyLink']){
			$output .="<div>Link: <a href='".$row['sciolyLink']."'>".$row['sciolyLink']."</a></div>";
		}
		if($row['description']){
			$output .="<div>Description: ".$row['description']."</div>";
		}

	endwhile;
	$output .="</div>";
}

echo $output;
?>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
