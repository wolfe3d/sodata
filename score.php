<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(4);
require_once  ("functions.php");
require_once  ("functionstournament.php");

$output = "";
$year = isset($_POST['myID'])?intval($_POST['myID']):getCurrentSOYear();
//$query = "SELECT `student`.`studentID`, `student`.`last`, `student`.`first` FROM `student` WHERE `student`.`active`";



//get all students active for selected year
//add students that are active and have not graduated, but do not show up on tournament
//go through score table
//include check boxes to include score.
//sum scores with javascript only


$returnBtn = "<input class='button fa' type='button' onclick='window.history.back()' value='&#xf0a8; Return' />";

$output .="<h2>Student Scores and Overall Placements</h2>";
$output .="<p class='warning'>This page is a beta version and calculations are likely to change.</p>";

function getAllStudentsParticipated($db, $year)
{
//Inactive students are not shown.  Students who are marked active, but students who have not competed are shown.
$students=[];
$query = "SELECT DISTINCT `student`.`studentID`, `student`.`yearGraduating`, `student`.`last`, `student`.`first` FROM `student` INNER JOIN `teammateplace` ON `student`.`studentID`=`teammateplace`.`studentID` INNER JOIN `team` ON `teammateplace`.`teamID`=`team`.`teamID` INNER JOIN `tournament` ON `team`.`tournamentID` = `tournament`.`tournamentID` WHERE `tournament`.`year`=$year AND `student`.`active`=1";
$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if($result->num_rows){
	while ($row = $result->fetch_assoc()):
		array_push($students, $row);
	endwhile;
	return $students;
}
return FALSE;
}

function checkScores($db,$tournamentID)
{
	//Get teammateplace
	$query = "SELECT `score`.`tournamentID` FROM `score` WHERE `score`.`tournamentID` = $tournamentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		//echo "number of rows". $resultTournament->num_rows;
		return TRUE;
	}
	return FALSE;
}

function getScores($db,$tournamentID)
{
	//Get teammateplace
	$scores = [];
	$query = "SELECT * FROM `score` WHERE `score`.`tournamentID` = $tournamentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		while ($row = $result->fetch_assoc()):
				array_push($scores, $row);
		endwhile;
		return $scores;
	}
	return FALSE;
}

//get tournaments
function getTournaments($db, $year)
{
	$tournaments = [];
	$query = "SELECT `tournament`.`tournamentID`, `tournament`.`tournamentName` FROM `tournament` WHERE `tournament`.`year`=$year ORDER BY `dateTournament`";
	$resultTournament = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		while ($row = $result->fetch_assoc()):
			if(checkScores($db, $row['tournamentID']))
			{
				array_push($tournaments, $row);
			}
		endwhile;
		return $tournaments;
	}
	return FALSE;
}

//get tournaments
function calculateOverallScores($db, &$students, $tournaments)
{
	foreach ($students as &$student)
	{
			$totalScore = 0;
			$tournamentCount = 0;
			$totalEvents = 0;
			$student['tournaments'] = [];
			foreach ($tournaments as $tournament)
			{
				$scoreStudent = "";
				$scores = getScores($db, $tournament['tournamentID']);
				$numEvents = 0;
				foreach ($scores as $score)
				{
					if ($score['studentID']==$student['studentID']&&$tournament['tournamentID']==$score['tournamentID'])
					{
						$scoreStudent = $score['score'];
						$totalScore += $scoreStudent;
						$tournamentCount += 1;
						$numEvents = $score['eventsNumber'];
						$totalEvents +=	$numEvents;
					}
				}
				array_push($student['tournaments'], ['tournamentID'=>$tournament['tournamentID'], 'score'=>$scoreStudent, 'eventsNumber'=>$numEvents]);
			}
			$student['count']=$tournamentCount;
			if ($totalScore)
			{
				$student['average']=number_format($totalScore/$tournamentCount,2);
			}
			else {
				$student['average']= 0;
			}
			if ($totalEvents)
			{
				$student['averageEvents']=number_format($totalEvents/$tournamentCount,2);
			}
			else {
				$student['averageEvents']= 0;
			}
			$student['score']= number_format($totalScore,2);
			$student['rank']= 0;
			//$output .= "<td>".$student['count']."</td><td>".number_format($student['avgPlace'],2)."</td><td id='score-".$student['studentID']."'>".number_format($student['score'],2)."</td><td id='rank-".$student['studentID']."'>".$student['rank']."</td></tr>";
		}
}


	$students = getAllStudentsParticipated($mysqlConn, $year);
	//print_r ($tournamentPlacements);
	$tournaments = getTournaments($mysqlConn, $year);
	//print_r ($events);
	calculateOverallScores($mysqlConn, $students, $tournaments);
	calculateTeamRanking($students);

	$output .="<table id='tournamentTable' class='tournament'>";
	$output .="<colgroup><col span=2>";
	foreach ($tournaments as $i=>$tournament)
	{
			$output .= "<col style='background-color:".rainbow($i)."'>";
	}
	$output .= "<col span=5></colgroup><thead><tr>";

	$output .="<th><div>Students</div><div><a href='javascript:tournamentSort(`studentLast`)'>Last</a>, <a href='javascript:tournamentSort(`studentFirst`)'>First</a></div></th>";
	$output .="<th><a href='javascript:tournamentSort(`grade`, 1)'>Grade</a></th>";

	//list all the tournament names in the header
	foreach ($tournaments as $tournament)
	{
		$output .= "<th rowspan='1' id='tournament-".$tournament['tournamentID']."'><a href='#tournament-score-".$tournament['tournamentID']."'>".$tournament['tournamentName']."</a></th>";
	}
	//$output .="<th rowspan='2'># Events</th>";
	//$output .="<th rowspan='2'>Avg Place</th>";
	$output .="<th rowspan='1'><a href='javascript:tournamentSort(`count`, 1)'>Total Tournaments</a></th>";
	$output .="<th rowspan='1'><a href='javascript:tournamentSort(`averageEvents`, 1)'>Average Events</a></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`average`, 1)'>Average Score</a></div><div>(Higher is Better)</div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`score`, 1)'>Total Score</a></div><div>(Higher is Better)</div></th>";
	$output .="<th rowspan='1'><div><a href='javascript:tournamentSort(`rank`, 1)'>Total Rank</a></div><div>(Lower is Better)</div></th>";

	//students header
	$output .="</tr></thead><tbody>";

	//list all the students and their events and score
	foreach ($students as $student)
	{
			$grade = getStudentGrade($student['yearGraduating']);
			$output .="<tr studentLast='".removeParenthesisText($student['last'])."'  studentFirst='".removeParenthesisText($student['first'])."' grade='$grade' count='".$student['count']."' average='".$student['average']."' averageEvents='".$student['averageEvents']."' score='".$student['score']."' rank='".$student['rank']."'>";
			$output .="<td class='student' id='teammate-".$student['studentID']."'><a target='_blank' href='#student-details-".$student['studentID']."'>".$student['last']. ", " . $student['first'] . "</a></td>";
			$output .="<td id='grade-".$student['studentID']."'>$grade</td>";

			$totalScore = 0;
			foreach ($student['tournaments'] as $tournament)
			{
				$output .= "<td id='studentscore-".$student['studentID']."-".$tournament['tournamentID']."' class='score-".$tournament['tournamentID']." student-".$student['studentID']."'>".$tournament['score']."</td>";
			}
			$output .= "<td id='count-".$student['studentID']."'>".$student['count']."</td>";
			$output .= "<td id='averageEvents-".$student['studentID']."'>".$student['averageEvents']."</td>";
			$output .= "<td id='average-".$student['studentID']."'>".$student['average']."</td>";
			$output .= "<td id='totalscore-".$student['studentID']."'>".$student['score']."</td>";
			$output .= "<td id='rank-".$student['studentID']."'>".$student['rank']."</td>";
			$output .="</tr>";
			//$output .= "<td>".$student['count']."</td><td>".number_format($student['avgPlace'],2)."</td><td id='score-".$student['studentID']."'>".number_format($student['score'],2)."</td><td id='rank-".$student['studentID']."'>".$student['rank']."</td></tr>";
		}
	$output .= "</tbody><table>";

	$output .= $returnBtn;

	$output .= "</form>";

echo $output;
?>
