<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);
require_once  ("php/functionstournament.php");

function changeTournamentScore($studentID, $tournamentID, $score, $averagePlace, $eventsNumber, $rank)
{
	global $mysqlConn;
	//change score table that holds each tournament score
	$query = "SELECT * FROM `score` WHERE `score`.`tournamentID` = $tournamentID AND `score`.`studentID` = $studentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		$row = $result->fetch_assoc();
		$query = "UPDATE `score` SET `eventsNumber` = $eventsNumber, `averagePlace` = $averagePlace, `score` = $score, `rank` = $rank WHERE `score`.`scoreID` = ".$row['scoreID'];
	}
	else {
		$query = "INSERT INTO `score` (`scoreID`, `studentID`, `tournamentID`, `eventsNumber`, `averagePlace`, `score`, `rank`) VALUES (NULL, $studentID, $tournamentID, $eventsNumber, $averagePlace, $score, $rank)";
	}
	if ($mysqlConn->query($query) === FALSE)
	{
		error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	}
}
function changeEventScore($student)
{
	global $mysqlConn;
	foreach ($student['events'] as $studentEvent)
	{
		//change teammateplace table with updated scores.
		$query = "UPDATE `teammateplace` SET `score` = ".$studentEvent['score']." WHERE `tournamenteventID` = ".$studentEvent['tournamenteventID']." AND `studentID` = ".$student['studentID'];
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if ($mysqlConn->query($query) === FALSE)
		{
			error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		}
	}
}

$output = "";
$tournamentID = intval($_POST['myID']);

//check to see if this tournament has placements
if(!checkPlacements($tournamentID))
{
	echo "<p>Placements have not been entered.  Enter placements in Assign Events page.</p>";
}
else
{
	$tournamentPlacements = getPlacements($tournamentID);
	//print_r ($tournamentPlacements);
	$events = getEvents($tournamentID);
	//print_r ($events);
	$students = getStudents($tournamentID);
	//print_r ($students);

	$tournamentWeight = getTournamentWeight($tournamentID);
	$teamsAttended = getTournamentTeamsAttended($tournamentID);
	calculateScores($students, $tournamentPlacements, $events, $tournamentWeight, $teamsAttended);
	calculateTeamRanking($students);

	foreach ($students as $student)
	{
	  changeTournamentScore($student['studentID'], $tournamentID, $student['score'], $student['avgPlace'], $student['count'], $student['rank']);
		changeEventScore($student);
	}

	echo "1";
}
?>
