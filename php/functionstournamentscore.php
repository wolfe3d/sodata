<?php
require_once  ("php/functionstournament.php");

function getAllStudentsParticipated($year)
{
	global $mysqlConn, $schoolID;
	//Inactive students are shown during this school year, but will be shown for other years.  Students who have not graduated, but students who have not competed are shown.
	$students=[];
	$studentYearQuery = "`student`.`active`=1";
	if ($year!=getCurrentSOYear())
	{
		$studentYearQuery = "`student`.`yearGraduating`>= $year";
	}
	$query = "SELECT DISTINCT `student`.`studentID`, `student`.`yearGraduating`, `student`.`last`, `student`.`first` FROM `student` 
	INNER JOIN `teammateplace` ON `student`.`studentID`=`teammateplace`.`studentID` 
	INNER JOIN `team` ON `teammateplace`.`teamID`=`team`.`teamID` 
	INNER JOIN `tournament` ON `team`.`tournamentID` = `tournament`.`tournamentID` 
	WHERE `tournament`.`year`=$year AND $studentYearQuery AND `student`.`schoolID`=$schoolID";
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
	//TODO REMOVE $resultTournament = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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

//get Attendance score
function calculateAttendance($studentID, $year)
{
	global $mysqlConn;
	$query = "SELECT * FROM `competitionyear` WHERE `year` = $year";
	$resultYear = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$startDate = "0000-00-00";
	$endDate = "3000-00-00";
	if($resultYear->num_rows){
		$rowYear = $resultYear->fetch_assoc();
		$startDate = $rowYear['startDate'];
		$endDate = $rowYear['endDate'];
	}


	$query = "SELECT `meetingattendance`.`studentID`, SUM(`meetingattendance`.`attendance`) AS `attendance`,SUM(`meetingattendance`.`engagement`) AS `engagement`,SUM(`meetingattendance`.`homework`) AS `homework` FROM `meetingattendance` 
	INNER JOIN `meeting` ON `meetingattendance`.`meetingID`=`meeting`.`meetingID`
	WHERE `meeting`.`meetingDate`>= '$startDate' AND `meeting`.`meetingDate`<= '$endDate' 
	AND `meetingattendance`.`studentID` = $studentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		$row = $result->fetch_assoc();
		return intval($row['attendance'])+intval($row['engagement'])+intval($row['homework']);
	}
	return 0;
}

//get tournaments
function calculateOverallScores(&$students, $tournaments)
{
	global $mysqlConn;
	global $year;
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

			//attendance score
			$student['attendance']=calculateAttendance($student['studentID'],$year);

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
			$student['score']= number_format($totalScore+$student['attendance'],2,".","");
			$student['rank']= 0;
			//$output .= "<td>".$student['count']."</td><td>".number_format($student['avgPlace'],2)."</td><td id='score-".$student['studentID']."'>".number_format($student['score'],2)."</td><td id='rank-".$student['studentID']."'>".$student['rank']."</td></tr>";
		}
}

//get lowest alphabetic event
function getEventAlphaYear($year)
{
	global $mysqlConn;
	$year = intval($year);
	$query = "SELECT DISTINCT `event`.`eventID`,`event`.`event`,`event`.`type` FROM `event` INNER JOIN `eventyear` ON `event`.`eventID`=`eventyear`.`eventID` WHERE `eventyear`.`year`=$year ORDER BY `event` ASC LIMIT 0,1";
	$resultEventsList = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($resultEventsList)
	{
		while ($row = $resultEventsList->fetch_assoc()):
			return $row['eventID'];
		endwhile;
	}
	return 0;
}

//get Attendance score
function calculateAttendanceEvent($studentID, $year, $eventID)
{
	global $mysqlConn;
	$query = "SELECT * FROM `competitionyear` WHERE `year` = $year";
	$resultYear = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$startDate = "0000-00-00";
	$endDate = "3000-00-00";
	if($resultYear->num_rows){
		$rowYear = $resultYear->fetch_assoc();
		$startDate = $rowYear['startDate'];
		$endDate = $rowYear['endDate'];
	}


	$query = "SELECT `meetingattendance`.`studentID`, SUM(`meetingattendance`.`attendance`) AS `attendance`,SUM(`meetingattendance`.`engagement`) AS `engagement`,SUM(`meetingattendance`.`homework`) AS `homework` FROM `meetingattendance` 
	INNER JOIN `meeting` ON `meetingattendance`.`meetingID`=`meeting`.`meetingID`
	WHERE `meeting`.`meetingDate`>= '$startDate' AND `meeting`.`meetingDate`<= '$endDate'
	AND `meeting`.`eventID`= '$eventID' 
	AND `meetingattendance`.`studentID` = $studentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		$row = $result->fetch_assoc();
		return intval($row['attendance'])+intval($row['engagement'])+intval($row['homework']);
	}
	return 0;
}

//get EventID from tournamentevent
function getEventID($tournamentEventID)
{
	global $mysqlConn;
	$query = "SELECT `eventID` FROM `tournamentevent` WHERE `tournamenteventID`=$tournamentEventID" ;
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows>0){
		$row = $result->fetch_assoc();
		return $row['eventID'];
	}
	return 0;
}

//get Event scores
function calculateEventScores(&$students, $tournaments, $eventID)
{
	global $mysqlConn;
	global $year;
	foreach ($students as &$student)
	{
			$totalScore = 0;
			$tournamentCount = 0;
			$totalPlace = 0;
			$student['places'] = [0,0,0,0,0,0];
			$student['tournaments'] = [];
			foreach ($tournaments as $tournament)
			{
				$scoreStudent = "";
				$placementStudent = "";
				$teammateplaces = getPlaces($tournament['tournamentID']);
				foreach ($teammateplaces as $place)
				{
					if ($place['studentID']==$student['studentID']&&$tournament['tournamentID']==$place['tournamentID']&&getEventID($place['tournamenteventID'])==$eventID)
					{
						$student['places'] = tallyPlacements($place['place'], $student['places']);
						$scoreStudent = $place['score'];
						$placementStudent = ordinal($place['place'])." (".points(round($place['score'],1)).")";
						$totalScore += $scoreStudent;
						$tournamentCount += 1;
						$totalPlace += $place['place'];
					}
				}
				array_push($student['tournaments'], ['tournamentID'=>$tournament['tournamentID'], 'placement'=>$placementStudent, 'score'=>$scoreStudent, 'eventsNumber'=>1]);
			}
			$student['count']=$tournamentCount;

			//attendance score
//TODO: Change for event

			$student['attendance']=calculateAttendanceEvent($student['studentID'],$year, $eventID);

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
			$student['score']= number_format($totalScore+$student['attendance'],2,".","");
			$student['rank']= 0;
			//$output .= "<td>".$student['count']."</td><td>".number_format($student['avgPlace'],2)."</td><td id='score-".$student['studentID']."'>".number_format($student['score'],2)."</td><td id='rank-".$student['studentID']."'>".$student['rank']."</td></tr>";
		}
}
?>