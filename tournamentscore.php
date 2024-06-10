<?php
require_once  ("php/functions.php");
userCheckPrivilege(5);
require_once  ("php/functionstournament.php");

//calculation of score
// SUM(eventweighting/eventplacement^3) * tournamentWeight
//With this method number of events is weighted.

$output = "";
$tournamentID = intval($_POST['myID']);
$returnBtn = "<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button>";

if(empty($tournamentID))
{
	echo "<div style='color:red'>teamID is not set.</div>";
	exit();
}
$output .="<h2>".getTournamentName($tournamentID)."</h2>";
$output .="<h3>Tournament Teammate Placement and Score</h3>";
$output .="<p class='text-warning'>This page is a beta version and calculations are likely to change.</p>";
//scores are calculated in functionstournament
$output .="<p class='text-warning'>Current Formula for = (tournamentWeight-((eventPlace-1)*(tournamentWeight/(teamsAttended/4))))*eventWeight/100</p>";
//check to see if this tournament has placements
if(!checkPlacements($tournamentID))
{
	if(userHasPrivilege(3))
	{
		$output .="<p>Placements have not been entered.  Enter placements in Assign Events page.</p>";
	}
	else
	{
		$output .="<p>Placements are not available or have not been entered.  If you know, the placements are available, then ask database manager or coach to add them.</p>";
	}
	$output .= "<p>" . $returnBtn ."</p>";
}
else
{
	$tournamentPlacements = getPlacements($tournamentID);
	//print_r ($tournamentPlacements);
	$events = getEvents($tournamentID);
	//print_r ($events);
	$students = getStudents($tournamentID);
	//print_r ($students);
	$tallyPlaces = [0,0,0,0,0,0];

	$tournamentWeight = getTournamentWeight($tournamentID);
	$teamsAttended = getTournamentTeamsAttended($tournamentID);
	calculateScores($students, $tournamentPlacements, $events, $tournamentWeight, $teamsAttended);
	calculateTeamRanking($students);
	//$output .="<div><span id='notification'></span></div>";
	$output .="<form id='addTo' method='post' action='tournamentscoresave.php'><table id='tournamentTable' class='tournament table table-hover'>";
	if(userHasPrivilege(4))
	{
		$output .="<div><label for='tournamentWeight' style='display: inline-block'>Tournament Weight</label>";
		$output .="  <input id='tournamentWeight' type='number' class='form-control' min='0' max='999' value='".$tournamentWeight."' style='display: inline-block'/></div>";
		$output .="<div><label for='teamsAttended' style='display: inline-block'>Teams Attended</label>";
		$output .="  <input id='teamsAttended' type='number' class='form-control' min='0' max='999' value='".$teamsAttended."' style='display: inline-block'/></div>";
	}
	else
	{
		$output .="<p>Tournament Weight: $tournamentWeight</p>";
		$output .="<p>Teams Attended: $teamsAttended</p>";
	}

	$output .="<p><input type='checkbox' id='showPoints' name='showPoints' checked><label for='showPoints'>Show Points</label></p>";
		$output .="<p>Event Weighting equal to 100 means all teams participated and the event was run with full rules. Lower event weight indicates that the full rules or full number of teams did not participate.</p>";
	$output .="<colgroup><col span='2'>";
	foreach ($events as $i=>$event)
	{
			$output .= "<col style='background-color:".rainbow($i)."'>";
	}
	$output .= "</colgroup><thead><tr>";

	$output .="<th rowspan='1' style='vertical-align:bottom;' colspan='2'><div>Event Weighting</div></th>";
	//list all the eventweights in the header
	foreach ($events as $event)
	{
		$eventWeight =  $event['weight'];
		if(userHasPrivilege(4))
		{
			$eventWeight = "<input id='eventweight-".$event['tournamenteventID']."' class='form-control' type='number' min='1' max='999' value='".$event['weight']."'/>";
		}
		$output .= "<th rowspan='1' id='eventweightth-".$event['tournamenteventID']."'>$eventWeight</th>";
	}
	$output .="<th rowspan='2'><a href='javascript:tournamentSort(`tournamentTable`,`count`, 1)'># Events</a></th>";
	$output .="<th rowspan='2'><a href='javascript:tournamentSort(`tournamentTable`,`average`, 1)'>Avg Place</a></th>";
	$output .="<th rowspan='2'><div><a href='javascript:tournamentSort(`tournamentTable`,`score`, 1)'>Score</a></div><div>(Higher is Better)</div></th>";
	$output .="<th rowspan='2'><div><a href='javascript:tournamentSort(`tournamentTable`,`rank`, 1)'>Rank</a></div><div>(Lower is Better)</div></th>";

	//students header
	$output .="</tr><tr><th><div>Students</div><div><a href='javascript:tournamentSort(`tournamentTable`,`studentLast`)'>Last</a>, <a href='javascript:tournamentSort(`tournamentTable`,`studentFirst`)'>First</a></div></th>";
	$output .="<th><a href='javascript:tournamentSort(`tournamentTable`,`grade`, 1)'>Grade</a></th>";
	//list all the events in the header
	foreach ($events as $event)
	{
			$output .= "<th rowspan='1' id='event-".$event['tournamenteventID']."'><span>".$event['event']."</span></th>";
	}

	$output .="</tr></thead><tbody>";

	//list all the students and their events and score
	foreach ($students as $student)
	{
		$grade = getStudentGrade($student['yearGraduating'], $year);
		$averagePlace = $student['avgPlace']?number_format($student['avgPlace'],2):"";
		$totalScore = $student['score']?number_format($student['score'],2):0;
		$output .="<tr studentLast='".removeParenthesisText($student['last'])."'  studentFirst='".removeParenthesisText($student['first'])."' grade='$grade' count='".$student['count']."' average='$averagePlace' score='$totalScore' rank='".$student['rank']."'>";
		$output .="<td class='student' id='teammate-".$student['studentID']."'><a target='_blank' href='#student-details-".$student['studentID']."'>".$student['last'].", " . $student['first'] ."(".$student['teamName'] .")</a></td>";
		$output .="<td id='grade-".$student['studentID']."'>$grade</td>";
		foreach ($events as $event)
		{
			$placement = "";
			$score = "";
			$scoreprint ="";
			foreach ($student['events'] as $studentEvent)
			{
				if ($studentEvent['tournamenteventID']==$event['tournamenteventID'])
				{
					$placement = $studentEvent['place'];
					$tallyPlaces = tallyPlacements($placement,$tallyPlaces); //add placement for each student
					$score = $studentEvent['score'];
					$scoreprint = $score ? "(".number_format($score,2).")":"";
					break;
				}
			}
			$output .= "<td id='studentplace-".$student['studentID']."-".$event['tournamenteventID']."' class='event-".$event['tournamenteventID']." student-".$student['studentID']."' placement='$placement'><span class='placement'>$placement</span> <span class='score'>$scoreprint</score></td>";
		}
		$output .= "<td>".$student['count']."</td><td>".$averagePlace."</td><td id='score-".$student['studentID']."'>".$totalScore."</td><td id='rank-".$student['studentID']."'>".$student['rank']."</td></tr>";
	}
	$output .= "</tbody><table>";

	// Create a table for tally of places
	$output .= tallyPlacementsPrint($tallyPlaces);

	// Save function
	$output .= "<p>" . $returnBtn;
	if(userHasPrivilege(4))
	{
		$output .= " <button class='btn btn-dark' type='button' onclick='tournamentScoresSave(`$tournamentID`)'><span class='bi bi-save'></span> Save Scores</button>";
	}
	$output .= "</p></form>";
}

echo $output;
?>
