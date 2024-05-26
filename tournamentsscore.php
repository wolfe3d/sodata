<?php
require_once  ("php/functions.php");
userCheckPrivilege(5);
require_once  ("php/functionstournament.php");

$output = "";
$year = isset($_POST['myID'])?intval($_POST['myID']):getCurrentSOYear();

//$query = "SELECT `student`.`studentID`, `student`.`last`, `student`.`first` FROM `student` WHERE `student`.`active`";


//get all students active for selected year
//add students that are active and have not graduated, but do not show up on tournament
//go through score table
//include check boxes to include score.
//sum scores with javascript only


$returnBtn = "<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>";

$output .="<h2>Student Scores and Overall Placements - $year</h2>";
$output .="<p class='text-warning'>This page is a beta version and calculations are likely to change.</p>";

function getAllStudentsParticipated($year)
{
	global $mysqlConn, $schoolID;
	//Inactive students are not shown.  Students who are marked active, but students who have not competed are shown.
	$students=[];
	$query = "SELECT DISTINCT `student`.`studentID`, `student`.`yearGraduating`, `student`.`last`, `student`.`first` FROM `student` INNER JOIN `teammateplace` ON `student`.`studentID`=`teammateplace`.`studentID` INNER JOIN `team` ON `teammateplace`.`teamID`=`team`.`teamID` INNER JOIN `tournament` ON `team`.`tournamentID` = `tournament`.`tournamentID` WHERE `tournament`.`year`=".$year." AND `student`.`active`=1 AND `student`.`schoolID`=$schoolID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		while ($row = $result->fetch_assoc()):
			array_push($students, $row);
		endwhile;
		return $students;
	}
	return FALSE;
}

function checkScores($tournamentID)
{
	global $mysqlConn;
	//Get teammateplace
	$query = "SELECT `score`.`tournamentID` FROM `score` WHERE `score`.`tournamentID` = $tournamentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		//echo "number of rows". $resultTournament->num_rows;
		return TRUE;
	}
	return FALSE;
}

function getScores($tournamentID)
{
	global $mysqlConn;
	//Get teammateplace
	$scores = [];
	$query = "SELECT * FROM `score` WHERE `score`.`tournamentID` = $tournamentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		while ($row = $result->fetch_assoc()):
				array_push($scores, $row);
		endwhile;
		return $scores;
	}
	return FALSE;
}

function getPlaces($tournamentID)
{
	global $mysqlConn;
	//Get teammateplace
	$places = [];
	$query = "SELECT * FROM `teammateplace` INNER JOIN `team` ON `teammateplace`.`teamID` = `team`.`teamID` WHERE `team`.`tournamentID` = $tournamentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		while ($row = $result->fetch_assoc()):
				array_push($places, $row);
		endwhile;
		return $places;
	}
	return FALSE;
}

//get tournaments
function getTournaments($year)
{
	global $mysqlConn;
	$tournaments = [];
	$query = "SELECT `tournament`.`tournamentID`, `tournament`.`tournamentName` FROM `tournament` WHERE `tournament`.`year`=$year ORDER BY `dateTournament`";
	$resultTournament = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		while ($row = $result->fetch_assoc()):
			if(checkScores($row['tournamentID']))
			{
				array_push($tournaments, $row);
			}
		endwhile;
		return $tournaments;
	}
	return FALSE;
}

//get tournaments
function calculateOverallScores(&$students, $tournaments)
{
	global $mysqlConn;
	foreach ($students as &$student)
	{
			$totalScore = 0;
			$tournamentCount = 0;
			$totalEvents = 0;
			$totalPlace = 0;
			$student['places'] = [0,0,0,0,0,0];
			$student['tournaments'] = [];
			foreach ($tournaments as $tournament)
			{
				$scoreStudent = "";
				$scores = getScores($tournament['tournamentID']);
				$numEvents = 0;
				foreach ($scores as $score)
				{
					if ($score['studentID']==$student['studentID']&&$tournament['tournamentID']==$score['tournamentID'])
					{
						$scoreStudent = $score['score'];
						$totalScore += $scoreStudent;
						$tournamentCount += 1;
						$totalPlace += $score['averagePlace'];
						$numEvents = $score['eventsNumber'];
						$totalEvents +=	$numEvents;
					}
				}
				$teammateplaces = getPlaces($tournament['tournamentID']);
				foreach ($teammateplaces as $place)
				{
					if ($place['studentID']==$student['studentID']&&$tournament['tournamentID']==$place['tournamentID'])
					{
						$student['places'] = tallyPlacements($place['place'], $student['places']);
					}
				}
				array_push($student['tournaments'], ['tournamentID'=>$tournament['tournamentID'], 'score'=>$scoreStudent, 'eventsNumber'=>$numEvents]);
			}
			$student['count']=$tournamentCount;
			if ($totalPlace)
			{
				$student['averagePlace']=number_format($totalPlace/$tournamentCount,2,".","");
			}
			else {
				$student['averagePlace']= 0;
			}
			if ($totalScore)
			{
				$student['averageScore']=number_format($totalScore/$tournamentCount,2,".","");
			}
			else {
				$student['averageScore']= 0;
			}
			if ($totalEvents)
			{
				$student['averageEvents']=number_format($totalEvents/$tournamentCount,2,".","");
			}
			else {
				$student['averageEvents']= 0;
			}
			$student['score']= number_format($totalScore,2,".","");
			$student['rank']= 0;
			//$output .= "<td>".$student['count']."</td><td>".number_format($student['avgPlace'],2)."</td><td id='score-".$student['studentID']."'>".number_format($student['score'],2)."</td><td id='rank-".$student['studentID']."'>".$student['rank']."</td></tr>";
		}
}
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
			$grade = getStudentGrade($student['yearGraduating']);
			$output .="<tr studentLast='".removeParenthesisText($student['last'])."'  studentFirst='".removeParenthesisText($student['first'])."' grade='$grade' count='".$student['count']."' averagePlace='".$student['averagePlace']."' averageScore='".$student['averageScore']."' averageEvents='".$student['averageEvents']."' score='".$student['score']."' rank='".$student['rank']."' first='".$student['places'][0]."' second='".$student['places'][1]."' third='".$student['places'][2]."'>";
			$output .="<td class='student' id='teammate-".$student['studentID']."'><a target='_blank' href='#student-details-".$student['studentID']."'>".$student['last']. ", " . $student['first'] . "</a></td>";
			$output .="<td id='grade-".$student['studentID']."'>$grade</td>";

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

	$output .= tallyPlacementsPrint($tallyPlaces);

	$output .= $returnBtn;

	$output .= "</form>";

echo $output;
?>
