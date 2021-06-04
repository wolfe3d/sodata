<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

userCheckPrivilege(1);
//text output
$output = "";

$tournamentID = intval($_REQUEST['myID']);
$query = "SELECT * from `tournament` WHERE `tournament`.`tournamentID` = $tournamentID";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if(empty($result))
{
	echo "Query Tournament View Failed.";
	exit();
}

$row = $result->fetch_assoc();
$numberTeams = $row["numberTeams"];

//Get number of teams created
$query = "SELECT * FROM `team` WHERE `tournamentID` = $tournamentID";
$resultTeams = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$amountOfCreatedTeams = $resultTeams->num_rows;

	$output .="<div>";
 if($row)
 {
	 $output .="<div id='tournamentTitle'>".$row['name']." - " . $row['year'] . "</div>";

		if(userHasPrivilege(3))
		{
			$output .="<div><input class='button fa' type='button' onclick='window.location.hash=\"tournament-edit-".$row['tournamentID']."\"' value='&#xf0ad; Edit Information' />";
			//only show add teams button if there needs to be more teams added
			if($amountOfCreatedTeams<$numberTeams)
			{
				$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"tournament-teamadd-".$row['tournamentID']."\"' value='&#xf0c0; Add Teams' />";
			}
			$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"tournament-times-".$row['tournamentID']."\"' value='&#xf017; Time Blocks' />";
			$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"tournament-events-".$row['tournamentID']."\"' value='&#xf0c3; Events' />";
			$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"tournament-eventtime-".$row['tournamentID']."\"' value='&#xf073;  Choose Times' />";

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
			$output .="<br>";
		}
		while($rowTeam = $resultTeams->fetch_assoc()):
			if(userHasPrivilege(3))
			{
				$output .="<h2>Team".$rowTeam['teamName']."</h2><p><input class='button fa' type='button' onclick='window.location.hash=\"tournament-teamedit-".$rowTeam['teamID']."\"' value='&#xf0c0; Edit Team ".$rowTeam['teamName']."' />";
				$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"tournament-teamassign-".$rowTeam['teamID']."\"' value='&#xf06d; Assign Events' /></p>";
			}
			else {
				$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"tournament-teamassign-".$rowTeam['teamID']."\"' value='&#xf06d; View Events' /></p>";
			}
		endwhile;
	}
	$output .="</div>";
	$output .= "<input class='button fa' type='button' onclick=\"window.location='#tournaments'\" value='&#xf0a8; Return' />";
echo $output;
?>
