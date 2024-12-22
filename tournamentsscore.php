<?php
require_once  ("php/functions.php");
userCheckPrivilege(5);
require_once  ("php/functionstournamentscore.php");

$output = "";
$year = isset($_POST['myID'])?intval($_POST['myID']):getCurrentSOYear();

//$query = "SELECT `student`.`studentID`, `student`.`last`, `student`.`first` FROM `student` WHERE `student`.`active`";


//get all students who have not graduated for selected year
//add students that are active and have not graduated, but do not show up on tournament
//go through score table
//include check boxes to include score.
//sum scores with javascript only


$returnBtn = "<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>";
//text output
$output = "<div>" . getSOYears($year, 0) . "</div>";
$output .="<h2>Student Scores and Overall Placements - $year</h2>";
$output .="<p class='text-warning'>Overall placement alone does not determine team placement.</p>";


	$students = getAllStudentsParticipated($year);
	if (!$students)
	{
		exit ("No scores recorded for year $year");
	}
	//print_r ($tournamentPlacements);
	$tournaments = getTournaments($year);
	//print_r ($events);
	calculateOverallScores($students, $tournaments);
	calculateTeamRanking($students);

	$output .="<table id='tournamentTable' class='tournament table table-hover'>";
	$output .="<colgroup><col span=2>";
	foreach ($tournaments as $i=>$tournament)
	{
			$output .= "<col style='background-color:".rainbow($i)."'>";
	}
	$output .= "<col span=5></colgroup><thead><tr>";

	$output .="<th><div>Students</div><div><a href='javascript:tournamentSort(`tournamentTable`,`studentLast`)'>Last</a>, <a href='javascript:tournamentSort(`tournamentTable`,`studentFirst`)'>First</a></div></th>";
	$output .="<th><a href='javascript:tournamentSort(`tournamentTable`,`grade`, 1)'>Grade</a></th>";

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
	$output .="<th rowspan='1'><a href='javascript:tournamentSort(`tournamentTable`,`averageEvents`, 1)'>Average Events</a></th>";
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
	foreach ($students as $student)
	{
			$grade = getStudentGrade($student['yearGraduating'], $year);
			$output .="<tr studentLast='".removeParenthesisText($student['last'])."'  studentFirst='".removeParenthesisText($student['first'])."' grade='$grade' attendance='".$student['attendance']."' count='".$student['count']."' averagePlace='".$student['averagePlace']."' averageScore='".$student['averageScore']."' averageEvents='".$student['averageEvents']."' score='".$student['score']."' rank='".$student['rank']."' first='".$student['places'][0]."' second='".$student['places'][1]."' third='".$student['places'][2]."'>";
			$output .="<td class='student' id='teammate-".$student['studentID']."'><a target='_blank' href='#student-details-".$student['studentID']."'>".$student['last']. ", " . $student['first'] . "</a></td>";
			$output .="<td id='grade-".$student['studentID']."'>$grade</td>";

			//attendance score
			$output .= "<td id='attendance-".$student['studentID']."'>".$student['attendance']."</td>";


			$totalScore = 0;
			foreach ($student['tournaments'] as $tournament)
			{
				$output .= "<td id='studentscore-".$student['studentID']."-".$tournament['tournamentID']."' class='score-".$tournament['tournamentID']." student-".$student['studentID']."'>".$tournament['score']."</td>";
			}
			$output .= "<td id='count-".$student['studentID']."'>".$student['count']."</td>";
			$output .= "<td id='averageEvents-".$student['studentID']."'>".$student['averageEvents']."</td>";
			$output .= "<td id='averagePlace-".$student['studentID']."'>".$student['averagePlace']."</td>";
			$output .= "<td id='averageScore-".$student['studentID']."'>".$student['averageScore']."</td>";
			$output .= "<td id='totalscore-".$student['studentID']."'>".$student['score']."</td>";
			$output .= "<td id='rank-".$student['studentID']."'>".$student['rank']."</td>";

			$output .= "<td id='first-".$student['studentID']."'>".$student['places'][0]."</td>";
			$output .= "<td id='second-".$student['studentID']."'>".$student['places'][1]."</td>";
			$output .= "<td id='third-".$student['studentID']."'>".$student['places'][2]."</td>";

			$output .="</tr>";

			//tally Placements
			for ($n = 0; $n < count($tallyPlaces); $n++)
			{
				$tallyPlaces[$n] += $student['places'][$n];
			}
			//$output .= "<td>".$student['count']."</td><td>".number_format($student['avgPlace'],2)."</td><td id='score-".$student['studentID']."'>".number_format($student['score'],2)."</td><td id='rank-".$student['studentID']."'>".$student['rank']."</td></tr>";
		}
	$output .= "</tbody><table>";

	$output .= tallyPlacementsPrint($tallyPlaces, "Team Members");

	$output .= $returnBtn;

	$output .= "</form>";

echo $output;
?>
<script defer>
	$(document).ready(function() {
		$("#year").change(function(){
							window.location.hash = '#tournamentsscore--'+ $("#year option:selected").text();
		});
	});
</script>
