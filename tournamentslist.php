<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
//text output
$output = "";

$name = $mysqlConn->real_escape_string($_POST['tournamentName']);
$year = intval($_POST['tournamentYear']);

$query = "SELECT * from `tournament`  ";
//check to see what is searched for
if($name&&$year)
{
	$query .= " where `tournament`.`tournamentName` LIKE '$name' AND `tournament`.`year` LIKE '$year'";
}
else if($name)
{
	$query .= " where `tournament`.`tournamentName` LIKE '$name'";
}
else if($year)
{
	$query .= " where `tournament`.`year` LIKE '$year' ";
}

$query .= " ORDER BY `tournament`.`tournamentName` ASC";
$output .=userHasPrivilege(3)?$query:"";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$tournamentTitle = $row['tournamentName']." - ".$row['year'];
		$output .="<hr><h2>".$tournamentTitle."</h2>";
		$output .="<input class='button fa' type='button' onclick='window.location.hash=\"tournament-view-".$row['tournamentID']."\"' value='&#xf108; View Details' />";

		if($row['websiteHost'])
		{
			$output .="<div>Host: <a href='".$row['websiteHost']."'>".$row['host']."</a></div>";
		}
		else
		{
			$output .="<div>Host: ".$row['host']."</div>";
		}
		//Address of the tournament
		$output .="<div>Address: ".$row['address']."</div>";
		$output .="<div>Date Tournament: ".$row['dateTournament']."</div>";
		$output .="<div>Date Registration: ".$row['dateRegistration']."</div>";
		$output .="<div>Number of Teams Registered: ".$row['numberTeams']."</div>";
		$output .="<div>Weighting/Diffuculty (0-100, 50=local/small, 75=regional, 90=state, 100 is hardest=national level): ".$row['weighting']."</div>";
		if($row['type'])
		{
			$output .="<div>Type:".$row['type']."</div>";
		}
		if($row['note'])
		{
			$output .="<div>Note:".$row['note']."</div>";
		}
		if($row['websiteSciOly'])
		{
			$output .="<div>Scilympiad Competition Website: <a href='".$row['websiteSciOly']."'>".$row['websiteSciOly']."</a></div>";
		}

		//Show Director information to coaches only
		if(userHasPrivilege(3))
		{
			//Director Information
			$output .="<br><h3>Director Information</h3>";
			$director=$row['director']?$row['director']:($row['directorEmail']?$row['directorEmail']:"");
			if($row['directorEmail'])
			{
				$director = "<a href='".$row['directorEmail']."'>$director</a>";
			}
			if($director)
			{
				$output .="<div>Director: $director</div>";
			}
			else if($row['directorEmail'])
			{
				$output .="<div>Host: ".$row['host']."</div>";
			}
			if($row['monthRegistration'])
			{
				$dateObj   = DateTime::createFromFormat('!m', $row['monthRegistration']);
				$monthName = $dateObj->format('F'); // March
				$output .="<div>Normal Month Registration: ".$monthName."</div>";
			}

			$output .="<div>Host: ".$row['host']."</div>";
		}

		$output .= "Add Team Schedules Here";

	endwhile;
	$output .="</div>";
}
echo $output;
?>
