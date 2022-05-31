<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once("functions.php");

userCheckPrivilege(1);


function assignmentMade($db, $teamID)
{
	$query = "SELECT * from `teammateplace` WHERE `teammateplace`.`teamID` = $teamID";
	$result = $db->query($query) or print("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(empty($result))
	{
		return 0;
	}
	return $result->num_rows;
}
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
$userID = $_SESSION['userData']['userID'];
$studentID = getStudentID($mysqlConn,$userID);

//Get number of teams created
$query = "SELECT * FROM `team` WHERE `tournamentID` = $tournamentID ORDER BY `teamName`";
$resultTeams = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$amountOfCreatedTeams = $resultTeams->num_rows;

	$output .="<div>";
 if($row)
 {
	 $output .="<div id='myTitle'>".$row['tournamentName']." - " . $row['year'] . "</div>";

		if(userHasPrivilege(3))
		{
			//tournament edit button -> changes hash to tournament-edit-tournamentID
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
		if($row['address']){
			$output .="<div>Address: <a href='https://www.google.com/maps/search/?api=1&query=".$row['address']."'>".$row['address']."</a></div>";
		}
		$output .="<div>Date Tournament: ".$row['dateTournament']."</div>";
		$output .="<div>Number of Teams Registered: ".$row['numberTeams']."</div>";
		$output .="<div>Weight/Difficulty (0-100, 50=local/small, 75=regional, 90=state, 100 is hardest=national level): ".$row['weight']."</div>";
		if($row['type'])
		{
			switch ($row['type']){
			case 1:
				$output .="<div>Type: Full</div>";
				break;
			case 2:
				$output .="<div>Type: Mini</div>";
				break;
			case 3:
				$output .="<div>Type: Hybrid</div>";
				break;
			}
		}
		if($row['note'])
		{
			$output .="<div>Note: ".$row['note']."</div>";
		}
		if($row['websiteScilympiad'])
		{
			$output .="<div>Scilympiad Competition Website: <a href='".$row['websiteScilympiad']."'>".$row['websiteScilympiad']."</a></div>";
		}

		//Show Director information to coaches only
		if(userHasPrivilege(3))
		{
			//Director Information
			$output .="<br><h3>Registration Information</h3>";
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
			if($row['directorPhone'])
			{
				$output .="<a href='tel:+".$row['directorPhone']."'>".$row['directorPhone']."</a>";
			}
			if($row['dateRegistration'])
			{
				$output .="<div>Date Registration: ".$row['dateRegistration']."</div>";
			}
			if($row['addressBilling'])
			{
				$output .="<div>Billing: ".$row['addressBilling']."</div>";
			}
			$output .="<br>";
		}
		$schedule ="";
		if(userHasPrivilege(3))
		{
			$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"tournament-emails-".$tournamentID."\"' value='&#xf01c; Get all teams' /></p>";
			$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"tournament-parentemails-".$tournamentID."\"' value='&#xf01c; Get all parents' /></p>";
		}
		if($studentID)
		{
			$schedule.=studentTournamentSchedule($mysqlConn, $tournamentID, $studentID);
			if($schedule != -1){
				$output .="<h3>My Schedule (Team ".getStudentTeam($mysqlConn, $tournamentID, $studentID).")</h3>";
				$output.=$schedule;
			}
			else{
				$output .= "You have not been assigned to events at this tournament.<br><br>";
			}
		}

		while($rowTeam = $resultTeams->fetch_assoc()):
			$output .="<h2>Team ".$rowTeam['teamName']."</h2>";
			if(userHasPrivilege(3))
			{
				$output .="<p><input class='button fa' type='button' onclick='window.location.hash=\"tournament-teamedit-".$rowTeam['teamID']."\"' value='&#xf0c0; Edit Team ".$rowTeam['teamName']."' />";
				if(!assignmentMade($mysqlConn, $rowTeam['teamID'])||userHasPrivilege(4))
				{
						$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"tournament-teampropose-".$rowTeam['teamID']."\"' value='&#xf06d; Propose Assignments' />";
				}
				$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"tournament-teamassign-".$rowTeam['teamID']."\"' value='&#xf06d; Assign Events' />";
				$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"team-emails-".$rowTeam['teamID']."\"' value='&#xf01c; Get team emails' /></p>";
			}
			else {
				$output .=" <input class='button fa' type='button' onclick='window.location.hash=\"tournament-teamassign-".$rowTeam['teamID']."\"' value='&#xf06d; View Events' /></p>";
			}
		endwhile;

		$output .="<h2>Tournament Results</h2>";
		$output .="<p><input class='button fa' type='button' onclick='window.location.hash=\"tournament-score-$tournamentID\"' value='&#xf080; View Scores' /></p>";

	}
	$output .="</div>";

	$output .= "<p><button class='btn btn-outline-secondary' onclick='window.history.back()'><span class='fa fa-arrow-circle-left'></span> Return</button></p>";

echo $output;
?>
