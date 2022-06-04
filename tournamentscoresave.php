<?php
require_once  ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(4);
require_once  ("php/functions.php");
require_once  ("functionstournament.php");

function changeTournamentScore($db, $studentID, $tournamentID, $score, $averagePlace, $eventsNumber, $rank)
{
	//change score table that holds each tournament score
	$query = "SELECT * FROM `score` WHERE `score`.`tournamentID` = $tournamentID AND `score`.`studentID` = $studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		$row = $result->fetch_assoc();
		$query = "UPDATE `score` SET `eventsNumber` = $eventsNumber, `averagePlace` = $averagePlace, `score` = $score, `rank` = $rank WHERE `score`.`scoreID` = ".$row['scoreID'];
	}
	else {
		$query = "INSERT INTO `score` (`scoreID`, `studentID`, `tournamentID`, `eventsNumber`, `averagePlace`, `score`, `rank`) VALUES (NULL, $studentID, $tournamentID, $eventsNumber, $averagePlace, $score, $rank)";
	}
	if ($db->query($query) === FALSE)
	{
		error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	}
}
function changeEventScore($db, $student)
{
	foreach ($student['events'] as $studentEvent)
	{
		//change teammateplace table with updated scores.
		$query = "UPDATE `teammateplace` SET `score` = ".$studentEvent['score']." WHERE `tournamenteventID` = ".$studentEvent['tournamenteventID']." AND `studentID` = ".$student['studentID'];
		$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if ($db->query($query) === FALSE)
		{
			error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		}
	}
}

$output = "";
$tournamentID = intval($_POST['myID']);

//check to see if this tournament has placements
if(!checkPlacements($mysqlConn, $tournamentID))
{
	echo "<p>Placements have not been entered.  Enter placements in Assign Events page.</p>";
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

	foreach ($students as $student)
	{
	  changeTournamentScore($mysqlConn, $student['studentID'], $tournamentID, $student['score'], $student['avgPlace'], $student['count'], $student['rank']);
		changeEventScore($mysqlConn, $student);
	}

	echo "1";
}
?>
