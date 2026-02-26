<?php
require_once  ("../connectsodb.php");

function calculateScore($eventPlace, $eventWeight, $tournamentWeight, $teamsAttended)
{
	//formula for scoring here
	//	$score = ($tournamentWeight-(($eventPlace-1)*($tournamentWeight/$teamsAttended)))*$eventWeight/100;
	//updated as 02/25/2026
	$score = ($tournamentWeight-(($eventPlace-1)*($tournamentWeight/($teamsAttended*($tournamentWeight/100/2)))))*$eventWeight/100;
	if ($score <=5)
	{
		return 5;
	}
	return $score;
}

//finds if placements have been added, so that a score may be calculated
function checkPlacements($tournamentID)
{
	global $mysqlConn;
	//Get teammateplace
	$query = "SELECT `teammateplace`.`studentID` FROM `teammateplace` INNER JOIN `team` ON `teammateplace`.`teamID` = `team`.`teamID` WHERE `team`.`tournamentID` = $tournamentID AND `teammateplace`.`place` IS NOT NULL";
	$resultTournament = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($resultTournament->num_rows){
		//echo "number of rows". $resultTournament->num_rows;
		return TRUE;
	}
	return FALSE;
}
//get the student placements from this tournament
function getPlacements($tournamentID)
{
	global $mysqlConn;
	$placements = [];
	//Get teammateplace
	$query = "SELECT DISTINCT `teammateplace`.`studentID`, `student`.`last`, `student`.`first`, `teammateplace`.`place`, `team`.`teamName`, `event`.`event`, `tournamentevent`.`tournamenteventID` FROM `teammateplace` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` INNER JOIN `team` ON `teammateplace`.`teamID` = `team`.`teamID` INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID`=`tournamentevent`.`tournamenteventID` INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID` WHERE `team`.`tournamentID` = $tournamentID AND `teammateplace`.`place` IS NOT NULL ORDER BY `student`.`last`, `student`.`first`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		while ($row = $result->fetch_assoc()):
			array_push($placements, $row);
		endwhile;
		return $placements;
	}
	return FALSE;
}

//get students who competed in this tournament
function getStudents($tournamentID)
{
	global $mysqlConn;
	$output = [];
	$query = "SELECT DISTINCT `student`.`studentID`,`student`.`yearGraduating`,`student`.`last`, `student`.`first`  FROM `student` INNER JOIN `teammateplace` ON `teammateplace`.`studentID`=`student`.`studentID` INNER JOIN `team` ON `teammateplace`.`teamID` = `team`.`teamID` WHERE `team`.`tournamentID` = $tournamentID ORDER BY `student`.`last`, `student`.`first`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		while ($row = $result->fetch_assoc()):
			array_push($output, $row);
		endwhile;
		return $output;
	}
	return FALSE;
}

//get events for this tournament
function getEvents($tournamentID)
{
	global $mysqlConn;
	$output = [];
	$query = "SELECT DISTINCT `tournamentevent`.`tournamenteventID`, `tournamentevent`.`weight`, `event`.`event` FROM `tournamentevent` INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID` WHERE `tournamentevent`.`tournamentID` = $tournamentID ORDER BY `event`.`event`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		while ($row = $result->fetch_assoc()):
			//set default weightings into the table
			if (!$row['weight'])
			{
				$weightDefault = isset($row['weightingDefault'])?$row['weightingDefault']:100;
				$query = "UPDATE `tournamentevent` SET `weight` = $weightDefault WHERE `tournamentevent`.`tournamenteventID` = ".$row['tournamenteventID'];
				$resultI = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
				$row['weight']=$weightDefault;
			}
			//push the event
			array_push($output, $row);
		endwhile;
		return $output;
	}
	return FALSE;
}

//get tournament weight
function getTournamentWeight($tournamentID)
{
	global $mysqlConn;
	$output = "";
	$query = "SELECT `tournament`.`weight` FROM `tournament` WHERE `tournament`.`tournamentID` = $tournamentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		if ($row = $result->fetch_assoc()){
			//set default weightings into the table
			return $row['weight'];
		}
	}
	return FALSE;
}

//get tournament weight
function getTournamentYear($tournamentID)
{
	global $mysqlConn;
	$output = "";
	$query = "SELECT `tournament`.`year` FROM `tournament` WHERE `tournament`.`tournamentID` = $tournamentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		if ($row = $result->fetch_assoc()){
			//set default weightings into the table
			return $row['year'];
		}
	}
	return FALSE;
}

//get number of teams competing in tournament
function getTournamentTeamsAttended($tournamentID)
{
	global $mysqlConn;
	$output = "";
	$query = "SELECT `tournament`.`teamsAttended` FROM `tournament` WHERE `tournament`.`tournamentID` = $tournamentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		if ($row = $result->fetch_assoc()){
			//set default weightings into the table
			return $row['teamsAttended'];
		}
	}
	return FALSE;
}

//get tournament weight
function getTournamentName($tournamentID)
{
	global $mysqlConn;
	$output = "";
	$query = "SELECT `tournament`.`tournamentName` FROM `tournament` WHERE `tournament`.`tournamentID` = $tournamentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		if ($row = $result->fetch_assoc()){
			//set default weightings into the table
			return $row['tournamentName'];
		}
	}
	return FALSE;
}

function calculateScores(&$students, $tournamentPlacements, $events, $tournamentWeight, $teamsAttended)
{
	foreach ($students as &$student)
	{
		//To use array_filter see https://stackoverflow.com/questions/32333436/php-multi-column-search-in-array
		$var1 = $student['studentID'];
		$studentPlacements = array_filter($tournamentPlacements, function($val) use($var1){
						return ($val['studentID']==$var1);
		});
		//calculate total events and average place
		$countEvents = 0;
		$totalPlace = 0;
		$student['score'] = 0;
		$teamName = "";
		$student['events'] = [];
		foreach ($studentPlacements as $value) {
			$totalPlace += $value['place'];
			$teamName = $value['teamName'];
			$countEvents += 1;
			$score = 0;
			//add event to student array with placement
			//$student[$value['tournamenteventID']]=$value['place'];

			//get weighting of event
			//$eventKey = array_search($value['tournamenteventID'], array_column($events, 'tournamenteventID'));
			foreach ($events as $event)
			{
				if($event['tournamenteventID'] == $value['tournamenteventID'])
				{
					//score student
					//$value['score']=$event['weight']/(($value['place'])**0.5)*($tournamentWeight/100)
					$score=calculateScore($value['place'], $event['weight'], $tournamentWeight, $teamsAttended);
					$studentEvent = ['tournamenteventID'=>$value['tournamenteventID'],'place'=>$value['place'],'score'=>$score];
					array_push($student['events'], $studentEvent );
					$student['score'] += $score; //total score
					break;
				}
			}
		}
		//store all values back in array
		$student['teamName'] = $teamName;
		$student['count']=$countEvents;
		if($countEvents)
		{
			$student['avgPlace']= $totalPlace/$countEvents;
		}
		else
		{
			$student['avgPlace']= "";
		}
	}
}

function compareScores($a, $b)
{
    if ($a['score'] == $b['score']) {
        return 0;
    }
    return ($a['score'] > $b['score']) ? -1 : 1;
}

function calculateTeamRanking(&$students)
{
	usort($students, "compareScores");
	foreach ($students as $n=>$student)
	{
		$students[$n]['rank'] = $n+1;
	}
}

function tournamentTimeChosenEmpty($tournamenteventID,$timeblockID)
{
	global $mysqlConn;
	$query = "SELECT * FROM `tournamenttimechosen` WHERE `tournamenteventID` = '$tournamenteventID' AND `timeblockID` = '$timeblockID';";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	if($result && $result->num_rows > 0)
	{
		return false;
	}
	return true;
}

function tournamentTimeChosenAllEmpty($tournamenteventID)
{
	global $mysqlConn;
	$query = "SELECT * FROM `tournamenttimechosen` WHERE `tournamenteventID` = '$tournamenteventID';";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	if($result && $result->num_rows > 0)
	{
		return false;
	}
	return true;
}

function tallyPlacements($place, $tally)
{
	if($place>0&&$place<7)
	{
		$tally[$place-1] +=1;
	}
	return $tally;
}
function tallyPlacementsPrint($tallyPlaces, $title)
{
	$output = "<h2>Placement Tally</h2>";
	$output .= "<table id='tally' class='table table-hover table-striped'>";
	$output .= "<thead class='table-dark'><tr><th>Place</th><th>$title</th></tr></thead><tbody>";
	//TODO: Add column for number of events earning a placement
	for ($n = 0; $n < count($tallyPlaces); $n++)
	{
		$place = ordinal($n+1);
		$output .= "<tr><td>" . $place . "</td><td>".$tallyPlaces[$n]."</td></tr>";
	}
	$output .= "</tbody></table>";
	return $output;
}
?>
