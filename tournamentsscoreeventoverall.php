<?php
require_once  ("php/functions.php");
userCheckPrivilege(5);
require_once  ("php/functionstournamentscore.php");


$output = "";
//get posted info
$year = isset($_POST['myID'])?intval($_POST['myID']):getCurrentSOYear();


$returnBtn = "<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>";
//text output
//$output = "<div>" . getSOYears($year, 0) . "</div>";
$output = "<div>" . getSOYears($year, 0) . "</div>";
$output .="<h2>Event Performance -  $year</h2>";
$output .="<p class='text-warning'>Event placement does not determine best event.</p>";

$events = getEventsYear($year);

	if (!$events)
	{
		exit ("No events recorded for year $year");
	}
	//print_r ($tournamentPlacements);
	$tournaments = getTournaments($year);
	//print_r ($events);
	calculateEventOverallScores($events, $tournaments);

	calculateTeamRanking($events);

	$output .="<table id='tournamentTable' class='tournament table table-hover'>";
	$output .="<colgroup><col span=2>";
	foreach ($tournaments as $i=>$tournament)
	{
			$output .= "<col style='background-color:".rainbow($i)."'>";
	}
	$output .= "<col span=5></colgroup><thead><tr>";

	$output .="<th rowspan='1'><a href='javascript:tournamentSort(`tournamentTable`,`event`, 0)'>Events</a></th>";

	//attendance score
	$output .="<th rowspan='1'><a href='javascript:tournamentSort(`tournamentTable`,`attendance`, 1)'>Attendance Score</a></th>";


	//list all the tournament names in the header
	foreach ($tournaments as $tournament)
	{
		$output .= "<th rowspan='1' id='tournament-".$tournament['tournamentID']."'><a href='#tournament-score-".$tournament['tournamentID']."'>".$tournament['tournamentName']."</a></th>";
	}
	//$output .="<th rowspan='2'># Events</th>";
	//$output .="<th rowspan='2'>Avg Place</th>";
	$output .="<th rowspan='1'><a href='javascript:tournamentSort(`tournamentTable`,`averagePlace`, 1)'>Average Place</a></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`tournamentScore`, 1)'>Total Tournament Score</a></div><div>(Higher is Better)</div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`averageScore`, 1)'>Average Tournament Score</a></div><div>(Higher is Better)</div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`score`, 1)'>Total Score</a></div><div>(Higher is Better)</div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`rank`, 1)'>Total Rank</a></div><div>(Lower is Better)</div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`first`, 0)'>1st Places</a></div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`second`, 1)'>2nd Places</a></div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`tournamentTable`,`third`, 1)'>3rd Places</a></div></th>";

	//table header
	$output .="</tr></thead><tbody>";

	//list all the events and their score
	$tallyPlaces = [0,0,0,0,0,0];
	foreach ($events as $event)
	{
			$output .="<tr event='".$event['event']."' attendance='".$event['attendance']."' averagePlace='".$event['averagePlace']."' averageScore='".$event['averageScore']."' score='".$event['score'] ."' tournamentScore='".$event['tournamentScore']."' rank='".$event['rank']."' first='".$event['places'][0]."' second='".$event['places'][1]."' third='".$event['places'][2]."'>";
			$output .="<td class='event' id='event-".$event['eventID']."'><a target='_blank' href='#event-details-".$event['eventID']."'>".$event['event']."</a></td>";

			//attendance score
			$output .= "<td id='attendance-".$event['eventID']."'>".$event['attendance']."</td>";


			$totalScore = 0;
			foreach ($event['tournaments'] as $tournament)
			{
				$output .= "<td id='eventscore-".$event['eventID']."-".$tournament['tournamentID']."' class='score-".$tournament['tournamentID']." event-".$event['event']."'>".$tournament['placement']."</td>";
			}
			$output .= "<td id='averagePlace-".$event['eventID']."'>".$event['averagePlace']."</td>";
			$output .= "<td id='tournamentScore-".$event['eventID']."'>".$event['tournamentScore']."</td>";
			$output .= "<td id='averageScore-".$event['eventID']."'>".$event['averageScore']."</td>";
			$output .= "<td id='totalscore-".$event['eventID']."'>".$event['score']."</td>";
			$output .= "<td id='rank-".$event['eventID']."'>".$event['rank']."</td>";

			$output .= "<td id='first-".$event['eventID']."'>".$event['places'][0]."</td>";
			$output .= "<td id='second-".$event['eventID']."'>".$event['places'][1]."</td>";
			$output .= "<td id='third-".$event['eventID']."'>".$event['places'][2]."</td>";

			$output .="</tr>";

			//tally Placements
			for ($n = 0; $n < count($tallyPlaces); $n++)
			{
				$tallyPlaces[$n] += $event['places'][$n];
			}
		}
	$output .= "</tbody><table>";
//TODO: This is teams not team members
	$output .= tallyPlacementsPrint($tallyPlaces, "Teams");

	$output .= $returnBtn;

	$output .= "</form>";

echo $output;
?>
<script defer>
	$(document).ready(function() {
		$("#year").change(function(){
			window.location.hash = '#tournamentsscoreeventoverall--'+ $("#year option:selected").text();
		});
	});
</script>