<?php
require_once  ("php/functions.php");
userCheckPrivilege(5);
require_once  ("php/functionstournamentscore.php");


$output = "";
//get posted info
$year = isset($_POST['myID'])?intval($_POST['myID']):getCurrentSOYear();

//$query = "SELECT `student`.`studentID`, `student`.`last`, `student`.`first` FROM `student` WHERE `student`.`active`";


//get all students who have not graduated for selected year
//add students that are active and have not graduated, but do not show up on tournament
//go through score table
//include check boxes to include score.
//sum scores with javascript only


$returnBtn = "<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>";
//text output
//$output = "<div>" . getSOYears($year, 0) . "</div>";
$output = "<div>" . getSOYears($year, 0) . "</div>";
$output .="<h2>Event Performance -  $year</h2>";
$output .="<p class='text-warning'>This page is a beta version and calculations are likely to change.</p>";
$events = getEventsYear(null,null, $year, null);

	$students = getAllStudentsParticipated($year);
	if (!$students)
	{
		exit ("No scores recorded for year $year");
	}
	//print_r ($tournamentPlacements);
	$tournaments = getTournaments($year);
	//print_r ($events);
	//calculateOverallScores($students, $tournaments);
	calculateEventScores($students, $tournaments, $eventID);

	calculateTeamRanking($students);

	$output .="<table id='tournamentTable' class='tournament table table-hover'>";
	$output .="<colgroup><col span=2>";
	foreach ($tournaments as $i=>$tournament)
	{
			$output .= "<col style='background-color:".rainbow($i)."'>";
	}
	$output .= "<col span=5></colgroup><thead><tr>";

	$output .="<th rowspan='1'><a href='javascript:tournamentSort(`tournamentTable`,`event`, 1)'>Events</a></th>";

	//attendance score
	$output .="<th rowspan='1'><a href='javascript:tournamentSort(`tournamentTable`,`attendance`, 1)'>Attendance Score</a></th>";


	//list all the tournament names in the header
	foreach ($tournaments as $tournament)
	{
		$output .= "<th rowspan='1' id='tournament-".$tournament['tournamentID']."'><a href='#tournament-score-".$tournament['tournamentID']."'>".$tournament['tournamentName']."</a></th>";
	}
	//$output .="<th rowspan='2'># Events</th>";
	//$output .="<th rowspan='2'>Avg Place</th>";
	$output .="<th rowspan='1'><a href='javascript:tournamentSort(`tournamentTable`,`count`, 1)'>Total Tournaments</a></th>";
	$output .="<th rowspan='1'><a href='javascript:tournamentSort(`tournamentTable`,`averagePlace`, 1)'>Average Place</a></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`averageScore`, 1)'>Average Score</a></div><div>(Higher is Better)</div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`score`, 1)'>Total Score</a></div><div>(Higher is Better)</div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`rank`, 1)'>Total Rank</a></div><div>(Lower is Better)</div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`first`, 0)'>1st Places</a></div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`second`, 1)'>2nd Places</a></div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`third`, 1)'>3rd Places</a></div></th>";

	//students header
	$output .="</tr></thead><tbody>";

	//list all the students and their events and score
	$tallyPlaces = [0,0,0,0,0,0];
	foreach ($events as $event)
	{
			$output .="<tr event='".$event['eventName']."' attendance='".$event['attendance']."' count='".$event['count']."' averagePlace='".$event['averagePlace']."' averageScore='".$event['averageScore']."' score='".$event['score']."' rank='".$event['rank']."' first='".$event['places'][0]."' second='".$event['places'][1]."' third='".$event['places'][2]."'>";
			$output .="<td class='event' id='event-".$event['eventID']."'><a target='_blank' href='#event-details-".$event['eventID']."'>".$event['eventName']."</a></td>";

			//attendance score
			$output .= "<td id='attendance-".$event['studentID']."'>".$event['attendance']."</td>";


			$totalScore = 0;
			foreach ($event['tournaments'] as $tournament)
			{
				$output .= "<td id='studentscore-".$event['studentID']."-".$tournament['tournamentID']."' class='score-".$tournament['tournamentID']." student-".$event['studentID']."'>".$tournament['placement']."</td>";
			}
			$output .= "<td id='count-".$event['studentID']."'>".$event['count']."</td>";
			$output .= "<td id='averagePlace-".$event['studentID']."'>".$event['averagePlace']."</td>";
			$output .= "<td id='averageScore-".$event['studentID']."'>".$event['averageScore']."</td>";
			$output .= "<td id='totalscore-".$event['studentID']."'>".$event['score']."</td>";
			$output .= "<td id='rank-".$event['studentID']."'>".$event['rank']."</td>";

			$output .= "<td id='first-".$event['studentID']."'>".$event['places'][0]."</td>";
			$output .= "<td id='second-".$event['studentID']."'>".$event['places'][1]."</td>";
			$output .= "<td id='third-".$event['studentID']."'>".$event['places'][2]."</td>";

			$output .="</tr>";

			//tally Placements
			for ($n = 0; $n < count($tallyPlaces); $n++)
			{
				$tallyPlaces[$n] += $event['places'][$n];
			}
		}
	$output .= "</tbody><table>";

	$output .= tallyPlacementsPrint($tallyPlaces);

	$output .= $returnBtn;

	$output .= "</form>";

echo $output;
?>
<script defer>
	$(document).ready(function() {
		$("#year").change(function(){
			window.location.hash = '#tournamentsscoreevent--'+ $("#year option:selected").text();
		});
	});
</script>