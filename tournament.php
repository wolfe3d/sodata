<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

userCheckPrivilege(1);
//text output
$output = "";

$tournamentID = $mysqlConn->real_escape_string($_REQUEST['tournamentID']);

$query = "SELECT * from `tournament` INNER JOIN `tournamentinfo` ON `tournament`.`tournamentInfoID`= `tournamentinfo`.`tournamentInfoID` WHERE `tournament`.`tournamentID` = $tournamentID";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

/check to make sure the query was valid
if(empty($result))
{
	echo "Query Student Edit Failed.";
	exit();
}

	$output .="<div>";
 if($row = $result->fetch_assoc())
 {
		if(userHasPrivilege(3))
		{
			$output .="<div><input class='button fa' type='button' onclick='prepareTournamentEdit(\"".$row['tournamentID']."\")' value='&#xf0ad; Edit Information' />";
			$output .=" <input class='button fa' type='button' onclick='tournamentAddTeams(\"".$row['tournamentID']."\")' value='&#xf0c0; Add Teams' />";
			$output .=" <input class='button fa' type='button' onclick='tournamentAddTimeBlocks(\"".$row['tournamentID']."\")' value='&#xf017; Add Time Blocks' />";
			$output .=" <input class='button fa' type='button' onclick='tournamentAddEvents(\"".$row['tournamentID']."\")' value='&#xf0c3; Add Events' />";
			$output .="</div><br>";
		}
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
		}
	}
	$output .="</div>";
	$output .= "<input class='button fa' type='button' onclick=\"window.location='#tournaments'\" value='&#xf0a8; Return' />";
echo $output;
?>
