<?php
require_once("php/functions.php");
userCheckPrivilege(1);
$schoolID =$_SESSION['userData']['schoolID'] ;


function assignmentMade($db, $teamID)
{
	$query = "SELECT * from `teammateplace` WHERE `teammateplace`.`teamID` = $teamID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(empty($result))
	{
		return 0;
	}
	return $result->num_rows;
}
//text output
$output = "";

$tournamentID = intval($_REQUEST['myID']);
$query = "SELECT * from `tournament` WHERE `tournament`.`tournamentID` = $tournamentID AND `tournament`.`schoolID` = `schoolID` = " . $_SESSION['userData']['schoolID'];
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

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
	$published = intval($row['published']);
	 $output .="<div id='myTitle'>".$row['tournamentName']." - " . $row['year'] . "</div>";

		if(userHasPrivilege(3))
		{
			$output .="<div>";
			//tournament edit button -> changes hash to tournament-edit-tournamentID
			if(userHasPrivilege(5))
			{
				if($published)
				{
					$output .="<a id='publishBtn' class='btn btn-secondary' role='button' href='javascript:tournamentUnPublish(".$row['tournamentID'].")'><span class='bi bi-cup-hot'></span> Unpublish</a>";
				}
				else
				{
					$output .="<a id='publishBtn' class='btn btn-primary' role='button' href='javascript:tournamentPublish(".$row['tournamentID'].")'><span class='bi bi-cup'></span> Publish</a>";
				}
			}
			$output .=" <a class='btn btn-secondary' role='button' href='#tournament-edit-".$row['tournamentID']."'><span class='bi bi-pencil-square'></span> Edit Information</a>";
			//only show add teams button if there needs to be more teams added
			if($amountOfCreatedTeams<$numberTeams)
			{
				$output .=" <a class='btn btn-secondary' role='button' href='#tournament-teamadd-".$row['tournamentID']."'><span class='bi bi-plus-circle'></span> Add Teams</a>";
			}
			$output .=" <a class='btn btn-secondary' role='button' href='#tournament-times-".$row['tournamentID']."'><span class='bi bi-clock-history'></span> Time Blocks</a>";
			$output .=" <a class='btn btn-secondary' role='button' href='#tournament-events-".$row['tournamentID']."'><span class='bi bi-puzzle'></span> Events</a>";
			$output .=" <a class='btn btn-secondary' role='button' href='#tournament-eventtime-".$row['tournamentID']."'><span class='bi bi-clock'></span> Choose Times</a>";
			$output .="</div><br>";
		}

		if(!$row['notCompetition'])
		{
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
		}
	}
		$schedule ="";
		if(userHasPrivilege(3) && $published)
		{
			$output .="<p><div class='btn-group' role='group' aria-label='All Email Group'>";
			$output .="<a class='btn btn-secondary' role='button' href='#tournament-emails-".$row['tournamentID']."' data-toggle='tooltip' data-placement='top' title='Get all teammate emails'><span class='bi bi-envelope'></span> Teammates</a>";
			$output .=" <a class='btn btn-secondary' role='button' href='#tournament-parentemails-".$row['tournamentID']."' data-toggle='tooltip' data-placement='top' title='Get all parent emails'><span class='bi bi-envelope-heart'></span> Parents</a>";
			$output .="</div></p>";
		}
		if($studentID)
		{
			$heading ="My Schedule (Team ".getStudentTeam($mysqlConn, $tournamentID, $studentID).")";
			$output.=studentTournamentSchedule($mysqlConn, $tournamentID, $studentID, $heading, $row['year']);
		}

		if(userHasPrivilege(5) || $published)
		{
		while($rowTeam = $resultTeams->fetch_assoc()):
			$output .="<h2>Team ".$rowTeam['teamName'];
			if ($row["dateTournament"]<=getCurrentTimestamp($mysqlConn))
	 		{
				$output .=" - " . ordinal($rowTeam['teamPlace']).teamCalculateScoreStr($mysqlConn, $rowTeam['teamID']);
			}
			$output .= "</h2>";
			$output .="<p><div class='btn-group' role='group' aria-label='Team Buttons'>";
			if(userHasPrivilege(3))
			{
				if(userHasPrivilege(4)||!$rowTeam['locked'])
				{
					$output .="<a class='btn btn-primary' role='button' href='#tournament-teamedit-".$rowTeam['teamID']."' data-toggle='tooltip' data-placement='top' title='Edit Team ".$rowTeam['teamName'] ."'><span class='bi bi-pencil-square'></span> Edit</a>";
					if(!assignmentMade($mysqlConn, $rowTeam['teamID'])&&userHasPrivilege(4))
					{
						$output .=" <a class='btn btn-info' role='button' href='#tournament-teampropose-".$rowTeam['teamID']."' data-toggle='tooltip' data-placement='top' title='Possible team assignments'><span class='bi bi-tornado'></span> Propose</a>";
					}
					$output .=" <a class='btn btn-primary' role='button' href='#tournament-teamassign-".$rowTeam['teamID']."' data-toggle='tooltip' data-placement='top' title='Assign events to team ".$rowTeam['teamName'] ."'><span class='bi bi-clipboard-plus'></span> Assign</a>";
				}
				else
				{
					$output .=" <a class='btn btn-primary' role='button' href='#tournament-teamassign-".$rowTeam['teamID']."' data-toggle='tooltip' data-placement='top' title='View events to team ".$rowTeam['teamName'] ."'><span class='bi bi-clipboard-plus'></span> View</a>";
				}
				$output .=" <a class='btn btn-secondary' role='button' href='#team-emails-".$rowTeam['teamID']."' data-toggle='tooltip' data-placement='top' title='Get team ".$rowTeam['teamName'] ." emails'><span class='bi bi-envelope'></span> Email</a>";
			}
			else {
				$output .=" <a class='btn btn-primary' role='button' href='#tournament-teamassign-".$rowTeam['teamID']."' data-toggle='tooltip' data-placement='top' title='View events for all team  ".$rowTeam['teamName'] ."'><span class='bi bi-clipboard-data'></span> View</a>";
			}
			$output .="</div></p>";
		endwhile;
		$output .="<div><a class='btn btn-primary' role='button' href='#tournament-allassign-".$row['tournamentID']."'><span class='bi bi-people-fill'></span> All Teams</a></div><br>";
		}


		if(!$row['notCompetition'] && userHasPrivilege(5))
		{
			$output .= $rowTeam['notCompetition'];
			//there are no results for a team assignment, so this is only shown for a real tournament
			$output .="<h2>Tournament Results</h2>";
			$output .="<p><a class='btn btn-dark' role='button' href='#tournament-score-$tournamentID'  data-toggle='tooltip' data-placement='top' title='View scores for teammates'><span class='bi bi-chart-line'></span> Scores</a></p>";
		}
	}
	$output .="</div>";

	$output .= "<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>";

echo $output;
?>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
