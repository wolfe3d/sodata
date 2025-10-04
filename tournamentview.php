<?php
require_once("php/functions.php");
require_once  ("php/remove.php"); //Check to make sure user is logged in and has privileges

userCheckPrivilege(1);

//text output
$output = "";
$tournamentID = intval($_REQUEST['myID']);
$query = "SELECT * from `tournament` WHERE `tournament`.`tournamentID` = $tournamentID AND `tournament`.`schoolID` = `schoolID` = $schoolID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if(empty($result))
{
	echo "Query Tournament View Failed.";
	exit();
}

$row = $result->fetch_assoc();
$userID = $_SESSION['userData']['userID'];
$studentID = getStudentID($userID);

//Get number of teams created
$query = "SELECT * FROM `team` WHERE `tournamentID` = $tournamentID ORDER BY `teamName`";
$resultTeams = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$output .="<div>";
 if($row)
 {
	$published = intval($row['published']);
	 $output .="<div id='myTitle'>".$row['tournamentName']." - " . $row['year'] . "</div>";

	 	$output .="<div class='btn-group' role='group' aria-label='Top Buttons'>";
		if(userHasPrivilege(4))
		{
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

		}

		if(!$row['notCompetition'] && $row['dateTournament']<=date("Y-m-d") && isset($row['resultsLink']))
		{
			//there are no results for a team assignment, so this is only shown for a real tournament
			$output .=" <a class='btn btn-primary' role='button' href='".$row['resultsLink']."'><span class='bi bi-trophy'></span> Results</a>";
		}
		else
		{
			$output .=" <a class='btn btn-secondary' role='button' href='#tournament-teamadd-".$row['tournamentID']."'><span class='bi bi-plus-circle'></span> Add Teams</a>";
			$output .=" <a class='btn btn-secondary' role='button' href='#tournament-times-".$row['tournamentID']."'><span class='bi bi-clock-history'></span> Time Blocks</a>";
			$output .=" <a class='btn btn-secondary' role='button' href='#tournament-events-".$row['tournamentID']."'><span class='bi bi-puzzle'></span> Events</a>";
			$output .=" <a class='btn btn-secondary' role='button' href='#tournament-eventtime-".$row['tournamentID']."'><span class='bi bi-clock'></span> Choose Times</a>";
		}
		$output .="</div>";

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
			$heading ="My Schedule (Team ".getStudentTeam($tournamentID, $studentID).")";
			$output.=studentTournamentSchedule($tournamentID, $studentID, $heading, $row['year']);
		}

		if($resultTeams->num_rows && (userHasPrivilege(5) || $published ))
		{
		while($rowTeam = $resultTeams->fetch_assoc()):
			$output .="<div id='team-".$rowTeam['teamID']."'><h2>Team ".$rowTeam['teamName'];
			if ($row["dateTournament"]<=getCurrentTimestamp())
	 		{
				$output .=" - " . ordinal($rowTeam['teamPlace']).teamCalculateScoreStr($rowTeam['teamID']);
			}
			$output .= "</h2>";
			$output .="<p><div class='btn-group' role='group' aria-label='Team Buttons'>";
			if(userHasPrivilege(3))
			{
				if(userHasPrivilege(4)||!$rowTeam['locked'])
				{
					$output .="<a class='btn btn-primary' role='button' href='#tournament-teamedit-".$rowTeam['teamID']."' data-toggle='tooltip' data-placement='top' title='Edit Team ".$rowTeam['teamName'] ."'><span class='bi bi-pencil-square'></span> Edit</a>";
					if(!checkinTable('teammateplace','teamID',$rowTeam['teamID'])&&userHasPrivilege(4))//Checks to make sure no students are assigned to team

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
				if(!checkinTable('teammate','teamID',$rowTeam['teamID'])&&userHasPrivilege(5)) 	//Checks to make sure no students are assigned to team
				{
					$output .=" <a class='btn btn-danger' role='button' href='javascript:teamRemove(".$rowTeam['teamID'].",\"".$rowTeam['teamName']."\")' data-toggle='tooltip' data-placement='top' title='Remove team ".$rowTeam['teamName'] ."'><span class='bi bi-eraser'></span> Remove</a>";
				}
			}
			else {
				$output .=" <a class='btn btn-primary' role='button' href='#tournament-teamassign-".$rowTeam['teamID']."' data-toggle='tooltip' data-placement='top' title='View events for all team  ".$rowTeam['teamName'] ."'><span class='bi bi-clipboard-data'></span> View</a>";
			}
			$output .="</div></p></div>";
		endwhile;
		$output .="<div><a class='btn btn-primary' role='button' href='#tournament-allassign-".$row['tournamentID']."'><span class='bi bi-people-fill'></span> All Teams</a></div><br>";
		}


		if(!$row['notCompetition'] && userHasPrivilege(5)&&$row['dateTournament']<=date('Y-m-d'))
		{
			$output .= $rowTeam['notCompetition'];
			//there are no results for a team assignment, so this is only shown for a real tournament
			$output .="<h2>Tournament Results</h2>";
			$output .="<p><a class='btn btn-dark' role='button' href='#tournament-score-$tournamentID'  data-toggle='tooltip' data-placement='top' title='View scores for teammates'><span class='bi bi-bar-chart'></span> Scores</a></p>";
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
