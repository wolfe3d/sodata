<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
require_once  ("functions.php");
require_once  ("functionstournament.php");
//calculation of score
// SUM(eventweighting/eventplacement^3) * tournamentWeight
//With this method number of events is weighted.

$output = "";
$tournamentID = intval($_POST['myID']);
$returnBtn = "<input class='button fa' type='button' onclick='window.history.back()' value='&#xf0a8; Return' />";
if(empty($tournamentID))
{
	echo "<div style='color:red'>teamID is not set.</div>";
	exit();
}
$output .="<h2>".getTournamentName($mysqlConn, $tournamentID)."</h2>";
$output .="<h3>Tournament Teammate Placement and Score</h3>";
$output .="<p class='warning'>This page is a beta version and calculations are likely to change.</p>";
$output .="<p class='warning'>Current Formula for Score = (100-((eventPlace**2)/((tournamentWeight/100)*50)))*(eventWeight/100).</p>";
//check to see if this tournament has placements
if(!checkPlacements($mysqlConn, $tournamentID))
{
	if(userHasPrivilege(3))
	{
		$output .="<p>Placements have not been entered.  Enter placements in Assign Events page.</p>";
	}
	else
	{
		$output .="<p>Placements are not available or have not been entered.  If you know, the placements are available, then ask database manager or coach to add them.</p>";
	}
	$output .= $returnBtn;
}
else
{
	$tournamentPlacements = getPlacements($mysqlConn, $tournamentID);
	//print_r ($tournamentPlacements);
	$events = getEvents($mysqlConn, $tournamentID);
	//print_r ($events);
	$students = getStudents($mysqlConn, $tournamentID);
	//print_r ($students);

	$tournamentWeight = getTournamentWeight($mysqlConn, $tournamentID);
	calculateScores($students, $tournamentPlacements, $events, $tournamentWeight);
	calculateTeamRanking($students);
	//$output .="<div><span id='notification'></span></div>";
	$output .="<form id='addTo' method='post' action='fieldupdate.php'><table id='tournamentTable' class='tournament'>";
	if(userHasPrivilege(4))
	{
		$output .="<div><label for='tournamentWeight' style='display: inline-block'>Tournament Weight</label>";
		$output .="  <input id='tournamentWeight' type='number' min='0' max='999' value='".$tournamentWeight."' style='display: inline-block'/></div>";
	}
	else
	{
		$output .="Tournament Weight: $tournamentWeight";
	}
	$output .="<colgroup><col span='2'>";
	foreach ($events as $i=>$event)
	{
			$output .= "<col style='background-color:".rainbow($i)."'>";
	}
	$output .= "</colgroup><thead><tr>";

	$output .="<th rowspan='1' style='vertical-align:bottom;' colspan='2'><div>Event Weights</div></th>";
	//list all the eventweights in the header
	foreach ($events as $event)
	{
		$eventWeight =  $event['weight'];
		if(userHasPrivilege(4))
		{
			$eventWeight = "<input id='eventweight-".$event['tournamenteventID']."' type='number' min='1' max='999' value='".$event['weight']."'/>";
		}
		$output .= "<th rowspan='1' id='eventweightth-".$event['tournamenteventID']."'>$eventWeight</th>";
	}
	$output .="<th rowspan='2'><a href='javascript:tournamentSort(`count`, 1)'># Events</a></th>";
	$output .="<th rowspan='2'><a href='javascript:tournamentSort(`average`, 1)'>Avg Place</a></th>";
	$output .="<th rowspan='2'><div><a href='javascript:tournamentSort(`score`, 1)'>Score</a></div><div>(Higher is Better)</div></th>";
	$output .="<th rowspan='2'><div><a href='javascript:tournamentSort(`rank`, 1)'>Rank</a></div><div>(Lower is Better)</div></th>";

	//students header
	$output .="</tr><tr><th><div>Students</div><div><a href='javascript:tournamentSort(`studentLast`)'>Last</a>, <a href='javascript:tournamentSort(`studentFirst`)'>First</a></div></th>";
	$output .="<th><a href='javascript:tournamentSort(`grade`, 1)'>Grade</a></th>";
	//list all the events in the header
	foreach ($events as $event)
	{
			$output .= "<th rowspan='1' id='event-".$event['tournamenteventID']."'><span>".$event['event']."</span></th>";
	}

	$output .="</tr></thead><tbody>";

	//list all the students and their events and score
	foreach ($students as $student)
	{
		$grade = getStudentGrade($student['yearGraduating']);
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
					$score = $studentEvent['score'];
					$scoreprint = $score ? "(".number_format($score,2).")":"";
					break;
				}
			}
			$output .= "<td id='studentplace-".$student['studentID']."-".$event['tournamenteventID']."' class='event-".$event['tournamenteventID']." student-".$student['studentID']."' placement='$placement'>$placement $scoreprint</td>";
		}
		$output .= "<td>".$student['count']."</td><td>".$averagePlace."</td><td id='score-".$student['studentID']."'>".$totalScore."</td><td id='rank-".$student['studentID']."'>".$student['rank']."</td></tr>";
	}
	$output .= "</tbody><table>";

	$output .= $returnBtn;
	if(userHasPrivilege(4))
	{
		$output .= "&nbsp; <input class='button fa' type='button' onclick='javascript:tournamentScoresSave(`$tournamentID`)' value='&#xf073;  Save Scores' />";
	}
	$output .= "</form>";
}

echo $output;
?>
