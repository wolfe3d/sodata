<?php
//include file
require_once  ("../connectsodb.php");
require_once 'checksession.php';
$schoolID = $_SESSION['userData']['schoolID'];

//clean text so that it can be a variable in javascript
function cleanForJavascript($string)
{
	$string = str_replace("'", "", $string);
	$string = str_replace("\"", "", $string);
	$string = json_encode ($string);  //puts string in quotes for direct placement into javascript function
	return $string;
}
//get ordinal number 1st, 2nd, 3rd, and so on
function ordinal($n)
{
    if($n)
	{
	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($n % 100) >= 11) && (($n%100) <= 13))
        return $n. 'th';
    else
        return $n. $ends[$n % 10];
	}
    return "Place Not Entered";
}
//get all students in a select
function getAllStudents($active, $studentID)
{
	global $mysqlConn;
	$myOutput = "";
	$whereAND = $active==1?"AND `student`.`active` = 1":"";
	$query = "SELECT * from `student` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'] . " $whereAND ORDER BY `last`,`first` ASC";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	if($result)
	{
		$myOutput .="<select class='form-select' id='studentID' name='studentID' type='text' required>";
		$myOutput.="<option></option>";
		while ($row = $result->fetch_assoc()):
			$selected = $row['studentID']==$studentID ? " selected " : "";
			$myOutput.="<option value = '".$row['studentID']."'$selected>".$row['last'].", ".$row['first']."</option>";
		endwhile;
		$myOutput .="</select>";
	}
	return $myOutput;
}

//get student ID of user
function getStudentID($userID)
{
	global $mysqlConn;
	$query = "SELECT `studentID` from `student` where `userID` = $userID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['studentID'];
	}
	return 0;
}
//get coach ID of user
function getCoachID($userID)
{
	global $mysqlConn;
	$query = "SELECT `coachID` from `coach` where `userID` = $userID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['coachID'];
	}
	return 0;
}

//get Name of user using unique studentID from table.  This is not their school's student id.  Their school's student id is called 'studentschoolID'
function getStudentName($studentID)
{
	global $mysqlConn;
	$query = "SELECT `last`, `first` from `student` where `studentID` = $studentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['last'] . ", " . $row['first'];
	}
	return 0;
}

//get officer abbreviation
function getOfficerAbbr($position)
{
	switch ($position) {
		case "Captain":
			return "C";
		case "Vice-Captain":
			return "VC";
		case "Lead Developer":
			return "Dev";
		case "Database Manager":
			return "DB";
		case "Team Coordinator":
			return "TC";
		case "Build It Boss":
			return "B";
		case "Testing Coordinator":
			return "Test";
		case "Secretary":
			return "S";
	}
}

//find if the student is an officer and create a short information link
function getOfficerLink($studentID, $year)
{
	global $mysqlConn;
	$query = "SELECT `position` from `officer` where `studentID` = $studentID AND `year` = $year";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		if($row = $result->fetch_assoc())
		{
			return " <small><span class='badge bg-success' title='".$row['position']."'>".getOfficerAbbr($row['position'])."</span></small>";
		}
	}
	return "";	
}

//find if the student is an event leader and create a short information link
function getEventLeaderLink($studentID, $year)
{
	global $mysqlConn;
	$query = "SELECT `event` from `eventleader` 
	INNER JOIN `event` ON `eventleader`.`eventID`=`event`.`eventID` where `studentID` = $studentID AND `year` = $year";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		if($row = $result->fetch_assoc())
		{
			return " <small><span class='badge bg-info' title='".$row['event']." - Event Leader'>EL</span></small>";
		}
	}
	return "";	
}

//find if this student is the event leader of the event
function getEventLeaderThisEvent($studentID, $year, $eventID)
{
	global $mysqlConn;
	if($eventID)
	{
		$query = "SELECT `event` from `eventleader` 
		INNER JOIN `event` ON `eventleader`.`eventID`=`event`.`eventID` where `studentID` = $studentID AND `year` = $year AND `eventleader`.`eventID` = $eventID";
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if($result)
		{
			if($row = $result->fetch_assoc())
			{
				return " <small><span class='badge bg-info' title='".$row['event']." - Event Leader'>EL</span></small>";
			}
		}
	}
	return "";	
}

//find if this student is the event leader of the event
function getEventLeaderOnTeam($teamID, $year, $eventID)
{
	global $mysqlConn;
	if($eventID)
	{
		$query = "SELECT `event`,`last`,`first` from `eventleader` 
		INNER JOIN `event` ON `eventleader`.`eventID`=`event`.`eventID`
		INNER JOIN `teammate` ON `eventleader`.`studentID`=`teammate`.`studentID`
		INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID`
		 where `teamID` = $teamID AND `year` = $year AND `eventleader`.`eventID` = $eventID";
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if($result)
		{
			if($row = $result->fetch_assoc())
			{
				return " <small><span class='badge bg-info' title='".$row['first']." ".$row['last']." - Event Leader'>EL</span></small>";
			}
		}
	}
	return "";	
}

//get Name of the students school
function getCurrentSchoolName($schoolID)
{
	global $mysqlConn;
	$query = "SELECT `schoolName`, `divisionID` from `school` WHERE `schoolID` = $schoolID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['schoolName'] . " (" . $row['divisionID'] . " Division)";
	}
	return 'No school associated';
}

//get Division of the students school
function getCurrentSchoolDivision()
{
	global $mysqlConn, $schoolID;
	$query = "SELECT `divisionID` from `school` WHERE `schoolID` = $schoolID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['divisionID'];
	}
	return 0;
}

//check Post variables and others if set.
function getIfSet($value, $default = NULL)
{
	return isset($value) ? $value : $default;
}

//post note and link to edit a tournament's event's note
function eventNote($id, $note, $maxLength=10, $editable = 0)
{
	$myOutput = "";
	$noteShort = $note;
	if ($maxLength)
	{
		$noteShort = substr($note, 0, $maxLength);
		$noteShort .= strlen($note)>$maxLength ? "...":"";
	}
	$href = "href='#tournament-eventnote-$id'";
	$noteAlt = "";
	if($editable){
		$noteAlt = "<a $href>Add</a>";
	}
	$myOutput .= $noteShort ? "<a $href>$noteShort</a>" : $noteAlt;
	return $myOutput;
}

//edit time block in the teamassign page
function timeblockEdit($id, $time, $editable = 0)
{
	$myOutput = "$time";
	if($editable){
		$href = "href='#tournament-eventtimechange-$id'";
		$myOutput = "<a $href>$time</a>";
	}
	return $myOutput;
}

//get students previous results, use this also to just get Event list for a Team assignment
function studentTournamentResults($studentID)
{
	global $mysqlConn;
	$query = "SELECT DISTINCT `tournament`.`tournamentID`, `dateTournament`, `tournamentName` FROM `tournament` INNER JOIN `team` ON `tournament`.`tournamentID` = `team`.`tournamentID` INNER JOIN `teammateplace` ON `team`.`teamID` = `teammateplace`.`teamID` WHERE `teammateplace`.`studentID` = $studentID AND `place` IS NOT NULL AND `notCompetition` = 0 ORDER BY `dateTournament` DESC";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = "";
	if($result && mysqli_num_rows($result)>0)
	{
		$output .="<h3>Results</h3><ul>";
		while ($row = $result->fetch_assoc()):
			$output.="<div id='".$row['tournamentName']."'>";
			$output.="<li>".$row['tournamentName']." - " . $row['dateTournament'];
			$output.=" <a class='btn btn-secondary btn-sm' role='button' href='#tournament-view-".$row['tournamentID']."'><span class='bi bi-controller'></span> View Details</a></div>";
			$output.="</li>";
			//show results
			$output.=	studentEvents($row['tournamentID'], $studentID, true);
		endwhile;
		$output.="</ul></div>";
	}
	return $output;
}

//use student placements to calculate score
function teamCalculateScoreStr($teamID)
{
	$score=teamScoreReturn($teamID);
	if($score)
	{
		return " (".$score."pts)";
	}
	return "";
}
function teamScoreReturn($teamID)
{
	global $mysqlConn;
	//return saved score from team
	$query = "SELECT `teamScore` FROM `team` WHERE `teamID`=$teamID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0)
	{
		$row= $result->fetch_assoc();
		if (isset($row['teamScore']))
		{
			return $row['teamScore'];
		}
	}
	return "";
}
function teamCalculateScore($teamID)
{
	global $mysqlConn;
	//calculate score and save it
	$query = "SELECT SUM(te.`place`) as p FROM (SELECT DISTINCT `tournamenteventID`, `place` FROM `teammateplace` WHERE `teamID`=$teamID) te";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0)
	{
		$row= $result->fetch_assoc();
		$score = $row['p'];
		if ($score>0)
		{
			$query = "UPDATE `team` SET `teamScore` = '$score' WHERE `team`.`teamID` = $teamID";
			$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		}
	}
}

//check to see if tournament has assigned any students to team
function tournamentHasTeammates($tournamentID)
{
	global $mysqlConn;
	$query = "SELECT COUNT(*) FROM `teammate`
	INNER JOIN `student` ON `teammate`.`studentID` = `student`.`studentID`
	INNER JOIN `team` ON `teammate`.`teamID` = `team`.`teamID`
	INNER JOIN `tournament` ON `tournament`.`tournamentID` = `team`.`tournamentID`
	WHERE `tournament`.`tournamentID` = $tournamentID";
	//$query = "SELECT COUNT(*) FROM `teammateplace` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID` INNER JOIN `event` ON `tournamentevent`.`eventID` = `event`.`eventID` INNER JOIN `tournamenttimechosen` ON `teammateplace`.`tournamenteventID` = `tournamenttimechosen`.`tournamenteventID` INNER JOIN `timeblock` ON `tournamenttimechosen`.`timeblockID` = `timeblock`.`timeblockID` WHERE `tournamentevent`.`tournamentID` = $tournamentID ORDER BY `timeStart` ";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row=$result->fetch_assoc();
		if($row['COUNT(*)'])
		{
			return 1;
		}
	}
	return 0;
}

//check to see if this student is assigned to a team on the tournament
function tournamentHasThisTeammate($tournamentID, $studentID)
{
	global $mysqlConn;
	//Check if this student is on a team
	$query = "SELECT `teamName` FROM `teammate`
	INNER JOIN `student` ON `teammate`.`studentID` = `student`.`studentID`
	INNER JOIN `team` ON `teammate`.`teamID` = `team`.`teamID`
	INNER JOIN `tournament` ON `tournament`.`tournamentID` = `team`.`tournamentID`
	WHERE `tournament`.`tournamentID` = $tournamentID AND `student`.`studentID`= $studentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && $result->num_rows > 0)
	{
		$row=$result->fetch_assoc();
		return $row['teamName'];
	}
	return 0;
}

function tournamentPublished($tournamentID)
{
	global $mysqlConn;
	//Check to see if tournament is published
	$query = "SELECT `published` from `tournament` WHERE `tournament`.`tournamentID` = $tournamentID ";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && $result->num_rows > 0)
	{
		$row=$result->fetch_assoc();
		return intval($row['published']);
	}
	return 0;
}

function studentTournamentSchedule($tournamentID, $studentID, $heading='Your events and partners', $year)
{
	global $mysqlConn;
	$schedule="";
	//This query checks to see if there are students on this tournaments
	if(tournamentHasTeammates($tournamentID)&& tournamentPublished($tournamentID))
	{
		$schedule.="<h4>$heading</h4>";
		if($teamName = tournamentHasThisTeammate($tournamentID, $studentID))
		{
			$query = "SELECT DISTINCT `tournamentevent`.`tournamenteventID`, `teammateplace`.`teamID`,`event`.`event`,`event`.`eventID`,`tournamentevent`.`note`,`timeblock`.`timeStart`,`timeblock`.`timeEnd` FROM `teammateplace`			
			INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID`
			INNER JOIN `event` ON `tournamentevent`.`eventID` = `event`.`eventID`
			INNER JOIN `tournamenttimechosen` ON `teammateplace`.`tournamenteventID` = `tournamenttimechosen`.`tournamenteventID` AND `teammateplace`.`teamID` = `tournamenttimechosen`.`teamID`
			INNER JOIN `timeblock` ON `tournamenttimechosen`.`timeblockID` = `timeblock`.`timeblockID`
			WHERE `tournamentevent`.`tournamentID` = $tournamentID AND `teammateplace`.`studentID` = $studentID
			ORDER BY `timeStart`";
			$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
			if($result && $result->num_rows > 0){
				$schedule.="<table class='table table-hover table-striped'><thead class='table-dark'><tr><th>Time (Local)</th><th>Event</th><th>Partners</th></tr></thead><tbody>";
				while ($row = $result->fetch_assoc()):
					$schedule.="<tr><td>";
					if($row['timeStart']){
						$schedule.="<div><small class='text-muted'>" . date("D, M. j",strtotime($row["timeStart"])). "</small></div><div>" . 
						date("g:iA",strtotime($row["timeStart"]))."</div><div>".
						date("g:iA",strtotime($row["timeEnd"]))."</div>";
					}
					$schedule.="</td>";
					$schedule.="<td><div><strong>".$row['event']."</strong></div><div>".$row['note']."</div></td>";
					$schedule.="<td>".studentPartnersWithEmails($row['tournamenteventID'], $row['teamID'], $studentID, $year, $row['eventID']).	"</td>";
					$schedule.="</tr>";
				endwhile;
				$schedule.="</tbody></table>";
			}
			else {
				$schedule.="<div class='text-warning'>SAVE THE DATE.  You have been assigned to Team $teamName, but you have not been assigned events yet.</div>";
			}
		}
		else {
			$schedule.="<div class='text-warning'>SAVE THE DATE.  You have NOT been assigned to this team, but you may be added later.  Contact Coach/Leader if you cannot make it.</div>";
		}
	}
	else {
		$schedule.="<div class='text-warning'>SAVE THE DATE.  Students have not been scheduled for this tournament.</div>";
	}
	return $schedule;
}


//Function to print event's list of students
function printEmailTable ($query, $eventID, $year)
{
	global $mysqlConn, $schoolID;
	$output="";
	$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$emails[] = NULL;
	$schoolEmails[]=NULL;
	$rows = 0;
	$leaderIDs = getEventLeaderIDs($eventID, $year);
	if($result)
	{
		$rows = $result->num_rows;
		if ($rows > 0)
		{
			$firstRow = 1;
			while ($row = $result->fetch_assoc()):
				if($firstRow)
				{
					$output.="<table class='table table-hover table-striped'><thead class='table-dark'><tr><th>Name</th><th>Email</th></tr></thead><tbody>";
					$firstRow = 0;
				}
				$output.="<tr>";

				$output.="<td class='student' id='teammate-".$row['studentID']."'><a target='_blank' href='#student-details-".$row['studentID']."'>".$row['last'].", " . $row['first'] ."</a>";
				if(in_array($row['studentID'],$leaderIDs))
				{
					$output .=" *Event Leader";
				}
				$output.="</td>";
				$output.="<td><a href='mailto:".$row['email']."'>".$row['email']."</a>, <a href='mailto:".$row['emailSchool']."'>".$row['emailSchool']."</a></td>";
				$output.="</tr>";
				$emails[] = $row['email'];
				$schoolEmails[] = $row['emailSchool'];
			endwhile;
			if(userHasPrivilege(2))
			{
				$output.="<tr>";
				$output.="<td>Total:$rows</td>";
				$emailList = implode(';', array_filter($emails)); //array_filter removes null values
				$schoolEmailList= implode(';', array_filter($schoolEmails)); //array_filter removes null values
				$output.="<td><a href='mailto:$emailList'>Personal Emails</a>, <a href='mailto:$schoolEmailList'>School Emails</a>, <a href='mailto:$emailList;$schoolEmailList'>All Emails</a></td>";
				$output.="</tr>";
			}
			$output.="</tbody></table>";	
		}
	}
	return $output;
}

//get latest team schedule - also known as the notCompetition Tournament.
function getLatestTeamTournamentStudent($studentID)
{
	global $mysqlConn;
	$query = "SELECT DISTINCT `tournament`.`tournamentID`, `dateTournament`, `tournamentName`, `teamName`
	FROM `tournament` INNER JOIN `team` ON `tournament`.`tournamentID` = `team`.`tournamentID`
	INNER JOIN `teammateplace` ON `team`.`teamID` = `teammateplace`.`teamID`
	WHERE `teammateplace`.`studentID` = $studentID AND `notCompetition`=1 AND `published`=1
	ORDER BY `dateTournament` DESC";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = "";
	if($result && mysqli_num_rows($result)>0)
	{
		$row = $result->fetch_assoc();
		$output.="<div id='".$row['tournamentName']."'>";
		$output .="<h3>".$row['tournamentName']." - Team ". $row['teamName'] ." (" . $row['dateTournament']. ")</h3>";
		$output.="<div><a class='btn btn-primary' role='button' href='#tournament-view-".$row['tournamentID']."'><span class='bi bi-controller'></span> View Details</a></div>";
		$output.= studentEvents($row['tournamentID'], $studentID, false);
		$output.="</div>";
	}
	return $output;
}

//find partners for an event in a tournament
function studentPartners($tournamentEventID, $teamID, $studentID)
{
	global $mysqlConn;
	//check partner(s)
	$query = "SELECT `first`,`last` FROM `student`
	INNER JOIN `teammateplace` ON `student`.`studentID`=`teammateplace`.`studentID`
	WHERE `teammateplace`.`tournamenteventID`='$tournamentEventID'
	AND `teammateplace`.`teamID`='$teamID'
	AND NOT `teammateplace`.`studentID` = $studentID
	ORDER BY `student`.`last`, `student`.`first`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output ="";
	if ($result)
	{
		$partnerNum =mysqli_num_rows($result);
		if($partnerNum>0)
		{
			while ($row = $result->fetch_assoc()):
				if($output) $output.=$partnerNum>1 ? ", ":" and "; //adds comma if more than two partners or and to add last partner
				$output.=$row['first']." ".$row['last'];
				$partnerNum -=1;  //reduces partners left to add
			endwhile;
			return $output;
		}
	}
	return "No partner!";
}

//find partners for an event in a tournament and returns Emails with line breaks
function studentPartnersWithEmails($tournamentEventID, $teamID, $studentID, $year, $eventID)
{
	global $mysqlConn;
	//check partner(s)
	$output =  "";
	$query = "SELECT * FROM `teammateplace`
	INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID`
	WHERE `tournamenteventID` = $tournamentEventID and `teammateplace`.`teamID` = $teamID AND `student`.`studentID` != $studentID
	AND `teammateplace`.`studentID` IN (SELECT `studentID` FROM `teammate` WHERE `teammate`.`teamID` = $teamID)
	AND `teammateplace`.`teamID` = $teamID
	ORDER BY `student`.`last`, `student`.`first`";
	//The IN (Select...) Statement fixes not fully removed students from teammateplace. This should not happen except when two people (or two windows) are making edits.  If the student is removed from the team while someone adds an event, it will leave a ghost student assigned.
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0){
		while ($row = $result->fetch_assoc()):
			$output.= "<a href='#student-details-".$row['studentID']."'>" . $row['first']." ".$row['last']."</a>".
			getOfficerLink($row['studentID'], $year).
			getEventLeaderThisEvent($row['studentID'], $year, $eventID).
			" <br>"; //removed email <a href='mailto:".$row['email']."'>".$row['email']."</a>
		endwhile;
	}
	else {
		$output = "No partner!";
	}
	return $output;
}

//find partners for an event in a tournament and returns Emails with line breaks
function partnersWithEmails($tournamentEventID, $teamID, $year, $eventID=NULL)
{
	global $mysqlConn;
	//check partner(s)
	$output =  "";
	$query = "SELECT * FROM `teammateplace` 
	INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID`
	WHERE `tournamenteventID` = $tournamentEventID and `teamID` = $teamID
	AND `teammateplace`.`studentID` IN 
	(SELECT `studentID` FROM `teammate` WHERE `teammate`.`teamID` = $teamID)
	ORDER BY `student`.`last`, `student`.`first`";
	//The IN (Select...) Statement fixes not fully removed students from teammateplace. This should not happen except when two people (or two windows) are making edits.  If the student is removed from the team while someone adds an event, it will leave a ghost student assigned.

	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0){
		while ($row = $result->fetch_assoc()):
			$output.= "<a href='#student-details-".$row['studentID']."'>" . $row['first']." ".$row['last']."</a>".
			getOfficerLink($row['studentID'], $year);
			$output.=$eventID?getEventLeaderThisEvent($row['studentID'], $year, $eventID):"";
			$output.=" <br>"; //removed email <a href='mailto:".$row['email']."'>".$row['email']."</a>
		endwhile;
	}
	else {
		$output = "No partner!";
	}
	return $output;
}


//find Scilympiad ID
function studentScilympiadID($studentID)
{
	global $mysqlConn;
	$query = "SELECT `scilympiadID` FROM `student` WHERE `student`.`studentID`=$studentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output ="";
	if ($row = $result->fetch_assoc())
	{
		$output=$row['scilympiadID']?$row['scilympiadID']:"Not set";
	}
	return $output;
}

//find student courses completed
function studentCourseCompleted($studentID)
{
	global $mysqlConn;
	$output = "";
	$query = "SELECT * FROM `coursecompleted` t1 INNER JOIN `course` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=$studentID ORDER BY t2.`course` ASC";// where `field` = $fieldId";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0)
	{
		$output .="<h3>Courses Completed - Level</h3><ul>";
		while ($row = $result->fetch_assoc()):
			$output .= "<li id='courseCompleted-" . $row['coursecompletedID'] . "'>" . $row['course'] . " - " . $row['level'] . "</li>";
		endwhile;
		$output .= "</ul>";
	}
	return $output;
}


//find student's courses enrolled but not yet completed
function studentCourseEnrolled($studentID)
{
	global $mysqlConn;
	$output = "";
	$query = "SELECT * FROM `courseenrolled` t1 INNER JOIN `course` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=$studentID ORDER BY t2.`course` ASC";// where `field` = $fieldId";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0)
	{
		$output .="<h3>Courses Enrolled - Level</h3><ul>";
		while ($row = $result->fetch_assoc()):
			$output .= "<li id='courseEnrolled-" . $row['courseenrolledID'] . "'>" . $row['course'] . " - " . $row['level'] . "</li>";
		endwhile;
		$output .= "</ul>";
	}
	return $output;
}

//find student's event priority
function studentEventPriority($studentID)
{
	global $mysqlConn;
	$output = "";
	$query = "SELECT * FROM `eventchoice` INNER JOIN `eventyear` ON `eventchoice`.`eventyearID`=`eventyear`.`eventyearID` INNER JOIN `event` ON `eventyear`.`eventID`=`event`.`eventID` WHERE `eventchoice`.`studentID`=$studentID ORDER BY `eventyear`.`year` DESC, `eventchoice`.`priority` ASC";// where `field` = $fieldId";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if ($result && mysqli_num_rows($result)>0)
	{
		$output .="<h3>Event Priority</h3><ul>";
		while ($row = $result->fetch_assoc()):
			$output .= "<li id='eventPriority-" . $row['eventchoiceID'] . "'>" . $row['year'] . "-" . $row['priority'] . " " . $row['event'] . "</li>";
		endwhile;
		$output .="</ul>";
	}
	return $output;
}

//get student events from a specific tournament
function studentEvents($tournamentID, $studentID, $showPlace)
{
	global $mysqlConn;
	$eventQuery = "SELECT `teammateplace`.`tournamenteventID`, `teamID`, `event`, `tournamentevent`.`eventID`, `place` FROM `teammateplace` INNER JOIN `student` on `teammateplace`.`studentID` = `student`.`studentID` INNER JOIN `tournamentevent` on `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID` inner join `event` on `tournamentevent`.`eventID` = `event`.`eventID` where `tournamentID` = $tournamentID and `student`.`studentID` = $studentID ORDER BY `event`.`event`";
	$result = $mysqlConn->query($eventQuery) or error_log("\n<br />Warning: query failed:$eventQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = "";
	if ($result && mysqli_num_rows($result)>0)
	{
		$output = "<ul>";
		while ($row = $result->fetch_assoc()):
			//show results
			$output.="<li>".$row['event'];
			if($showPlace)
			{
				$output.=": ". $row['place'];
			}
			$output.=" (".studentPartners($row['tournamenteventID'], $row['teamID'], $studentID).")</li>";
		endwhile;
		$output .= "</ul>";
	}
	return $output;
}

//get student team name from a spceific tournament
function getStudentTeam($tournamentID, $studentID)
{
	global $mysqlConn;
	$eventQuery = "SELECT DISTINCT `teamName` FROM `teammateplace` inner join `student` on `teammateplace`.`studentID` = student.studentID inner join team on teammateplace.teamID = team.teamID where tournamentID = $tournamentID and student.studentID = $studentID";
	$result = $mysqlConn->query($eventQuery) or error_log("\n<br />Warning: query failed:$eventQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row = $result->fetch_assoc();
	return $row['teamName'];
}

//returns a bare list of names with multiple emails
function getEmailList($result)
{
	$emails = "";
	while ($row = $result->fetch_assoc()):
		$emails.=$row['first'] . " " . $row['last'] . " ";
		if(isset($row['email'])&&$row['email']){
			$emails.= "&lt;" . $row['email'] . "&gt;; ";
			$emails.="<br>";
		}

		if(isset($row['emailSchool'])&&$row['emailSchool']){
			$emails.="&lt;".$row['emailSchool'] . "&gt;; ";
			$emails.="<br>";
		}
	endwhile;
	return $emails;
}

//returns a bare list of names with multiple emails
function getEmailListWithTeam($result)
{
	$emails = "";
	while ($row = $result->fetch_assoc()):
		$emails.=$row['first'] . " " . $row['last'] . " (" . $row['teamName'] .")";
		if(isset($row['email'])&&$row['email']){
			$emails.= "&lt;" . $row['email'] . "&gt;; ";
			$emails.="<br>";
		}

		if(isset($row['emailSchool'])&&$row['emailSchool']){
			$emails.="&lt;".$row['emailSchool'] . "&gt;; ";
			$emails.="<br>";
		}
	endwhile;
	return $emails;
}

//returns a bare list of parent names with multiple emails
function getEmailParentList($result)
{
	$emails = "";
	while ($row = $result->fetch_assoc()):
		if($row['parent1Email']){
			$emails.=$row['parent1First'] . " " . $row['parent1Last']." ";
			$emails.="&lt;" . $row['parent1Email'] . "&gt;; ";
			$emails.="<br>";
		}
		if($row['parent2Email']){
			$emails.=$row['parent2First'] . " " . $row['parent2Last']." ";
			$emails.="&lt;" . $row['parent2Email'] . "&gt;; ";
			$emails.="<br>";
		}
	endwhile;
	return $emails;
}

//Return Coaches leader email list
function getCoachesEmails($year)
{
	global $mysqlConn, $schoolID;

	//$year = isset($year)?$year:getCurrentSOYear(); //assumes $year is an integer
	$query = "SELECT DISTINCT `first`, `last`, `emailSchool` FROM `coach` WHERE `schoolID` = $schoolID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	return getEmailList($result);
}

//Get Team Emails either students or parents
function getTeamEmails($teamID=NULL, $tournamentID=NULL, $parents)
{
	global $mysqlConn, $schoolID;

	$query = "SELECT DISTINCT `first`, `last`, `email`, `parent1First`, `parent1Last`,`parent1Email`,`parent2First`, `parent2Last`,`parent2Email`,`emailSchool` 
		FROM `student` 
		inner join `teammate` on `student`.`studentID` = `teammate`.`studentID` inner join `team` on `teammate`.`teamID` = `team`.`teamID` 
		where `active` = 1 AND `schoolID` = $schoolID";
	if($teamID){
		$query.=" AND `team`.`teamID` = $teamID";
	}
	if($tournamentID){
		$query.=" AND `tournamentID` = $tournamentID";
	}
	$query.=" Order by `last`, `first`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	if($parents){
		// Header
		$output = "<h2>Parent Emails</h2>";
		// Create a table for parent emails
		$output .= "<table id='emails' class='table table-hover table-striped'>";
		$output .= "<thead class='table-dark'><tr><th>Teammate</th><th>Parent</th><th>Email</th></tr></thead><tbody>";

		while ($row = $result->fetch_assoc()) {
			$output .= "<tr>";
			$output .= "<td>" . $row["first"] . " " . $row["last"] . "</td>";
			$output .= "<td>" . $row["parent1First"] . " " . $row["parent1Last"] ."</td>";
			$output .= "<td><a href='mailto:".$row['parent1Email']."'>".$row['parent1Email']."</a></td>";
			$output .= "</tr><tr>";
			$output .= "<td>" . $row["first"] . " " . $row["last"] . "</td>";
			$output .= "<td>" . $row["parent2First"] . " " . $row["parent2Last"] . "</td>";
			$output .= "<td><a href='mailto:".$row['parent2Email']."'>".$row['parent2Email']."</a></td>";
			$output .= "</tr>";

			$emails[] = $row['parent1Email'];
			$emails[] = $row['parent2Email'];
		}
		if(userHasPrivilege(3))
		{
			$output.="<tr>";
			$output.="<td>Total</td>";
			$emailList = implode(';', array_filter($emails)); //array_filter removes null values
		}
		// Copy email buttons
		$output .= "<td></td><td><p><button class='btn btn-primary' onclick='copyToClipboard(\"" . $emailList . "\")' type='button'><span class='bi bi-clipboard-plus'></span> Copy parent emails</button></p></td>";
		$output .= "</tr></tbody></table>";
	}
	else {
		// Header
		$output = "<h2>Team Emails</h2>";
		// Create a table for student emails
		$output .= "<table id='emails' class='table table-hover table-striped'>";
		$output .= "<thead class='table-dark'><tr><th>Name</th><th>Email</th><th>School Email</th></tr></thead><tbody>";

		while ($row = $result->fetch_assoc()) {
			$output .= "<tr>";
			$output .= "<td>" . $row["first"] . " " . $row["last"] . "</td>";
			$output .= "<td><a href='mailto:".$row['email']."'>".$row['email']."</a></td>";
			$output .= "<td><a href='mailto:".$row['emailSchool']."'>".$row['emailSchool']."</a></td>";
			$output .= "</tr>";

			$emails[] = $row['email'];
			$schoolEmails[] = $row['emailSchool'];
		}
		if(userHasPrivilege(3))
		{
			$output.="<tr>";
			$output.="<td>Total</td>";
			$studentEmailList = implode(';', array_filter($emails)); //array_filter removes null values
			$schoolEmailList= implode(';', array_filter($schoolEmails)); //array_filter removes null values
		}
		// Copy email buttons
		$output .= "<td><p><button class='btn btn-primary' onclick='copyToClipboard(\"" . $studentEmailList . "\")' type='button'><span class='bi bi-clipboard-plus'></span> Copy student emails</button></p></td>";
		$output .= "<td><p><button class='btn btn-primary' onclick='copyToClipboard(\"" . $schoolEmailList . "\")' type='button'><span class='bi bi-clipboard-plus'></span> Copy school emails</button></p></td>";
		$output .= "</tr></tbody></table>";
	}
	return $output;
}

//Return officer leader email list
function getOfficerEmails($year)
{
	global $mysqlConn, $schoolID;
	$year = isset($year)?$year:getCurrentSOYear(); //assumes $year is an integer
	$query = "SELECT DISTINCT `first`, `last`, `email`, `parent1First`, `parent1Last`,`parent1Email`,`parent2First`, `parent2Last`,`parent2Email`,`emailSchool` FROM `officer` INNER JOIN `student` ON `officer`.`studentID`= `student`.`studentID` WHERE `schoolID` = $schoolID AND `year`=$year";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	return getEmailList($result) . getCoachesEmails($year);
}

//Return event leader email list
function getLeaderEmails($year)
{
	global $mysqlConn, $schoolID;
	$year = isset($year)?$year:getCurrentSOYear(); //assumes $year is an integer
	$query = "SELECT DISTINCT `first`, `last`, `email`, `parent1First`, `parent1Last`,`parent1Email`,`parent2First`, `parent2Last`,`parent2Email`,`emailSchool` FROM `eventleader` INNER JOIN `student` ON `eventleader`.`studentID`= `student`.`studentID` INNER JOIN `event` ON `eventleader`.`eventID`=`event`.`eventID` WHERE `schoolID` = $schoolID AND `year`=$year";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] . ".");
	if($result && $result->num_rows>0){
		return getEmailList($result) . getCoachesEmails($year);
		}
	}

	//Return event leader email list
	/*function getEventEmails($tournamentID, $tournamentName, $eventID, $year)
	{
		global $mysqlConn;
		$output = "<h3>$tournamentName</h3>";
		$schoolID = $_SESSION['userData']['schoolID'];
		$leader = getEventLeaderIDs($eventID, $year);
		$query = "SELECT DISTINCT `student`.`studentID`, `event`.`event`, `first`, `last`, `email`, `teamName`, `emailSchool`
		FROM `tournament`
		INNER JOIN `team` ON `tournament`.`tournamentID`=`team`.`tournamentID`
		INNER JOIN `teammateplace` ON `team`.`teamID` = `teammateplace`.`teamID`
		INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID`=`tournamentevent`.`tournamenteventID`
		INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID`
		INNER JOIN `student` ON `teammateplace`.`studentID`= `student`.`studentID`
		WHERE `tournament`.`tournamentID` = '$tournamentID' AND `tournamentevent`.`eventID` = '$eventID'
		AND `student`.`schoolID` = '$schoolID'
		ORDER BY `teamName`,`last`,`first`";
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] . ".");
		while ($row = $result->fetch_assoc()):
			$output.=$row['first'] . " " . $row['last'] . " (" . $row['teamName'] .")";
			if(in_array($row['studentID'], $leader, $strict = true))
			{
				$output.=" **" . $row['event']. " Event Leader";
			}
			if(isset($row['email'])&&$row['email']){
				$output.= "&lt;" . $row['email'] . "&gt;; ";
				$output.="<br>";
			}

			if(isset($row['emailSchool'])&&$row['emailSchool']){
				$output.="&lt;".$row['emailSchool'] . "&gt;; ";
				$output.="<br>";
			}
		endwhile;
		return $output;
		}
*/

	//Return all active students or parents
	function getStudentEmails($year, $parents=false)
	{
		global $mysqlConn;
		//$year = isset($year)?$year:getCurrentSOYear(); //assumes $year is an integer
		$query = "SELECT DISTINCT `first`, `last`, `email`, `parent1First`, `parent1Last`,`parent1Email`,`parent2First`, `parent2Last`,`parent2Email`,`emailSchool` FROM `student` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'] . " AND `active`=1";
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$emails = "";
		if(!$parents){
			$emails = getEmailList($result);
		}
		else
		{
			$emails = getEmailParentList($result);
		}
		return $emails. getCoachesEmails(NULL);
}


//find the name of the event
function getEventName($eventID)
{
	global $mysqlConn;
	$query = "SELECT `event` FROM `event` WHERE `eventID`=$eventID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows>0){
		$row = $result->fetch_assoc();
		return $row['event'];
	}
	return 0;
}

//get Event type options
function getEventString($type)
{
	if (!$type || $type == 0){
		return "Core Knowledge (Test Only)";
	}
	elseif ($type == 1) {
		return "Build";
	}
	elseif ($type == 2) {
		return "Laboratory or Hands On";
	}
	elseif ($type == 3) {
		return "Hybrid Build";
	}
	elseif ($type == 4) {
		return "Hybrid Lab";
	}
}

//Return Calculator Type
function getCalulatorString($type)
{
	if (!$type || $type == 0){
		return "None";
	}
	elseif ($type == 1) {
		return "Graphing or any other type";
	}
	elseif ($type == 2) {
		return "4-Function Only";
	}
	elseif ($type == 3) {
		return "Scientific or 4-Function Only";
	}
}

//return Goggle Type
function getGoggleString($type)
{
	if (!$type || $type == 0){
		return "None";
	}
	elseif ($type == 1) {
		return "Class B - Impact only, for Most Builds";
	}
	elseif ($type == 2) {
		return "Class C - Splash Resistant, for Most Labs";
	}
}

//return Phone Type
function getPhoneString($type)
{
	switch (getIfSet($type, 0)) {
		case 1:
		return "home";
		break;
		case 2:
		return "parent cell";
		break;
		default:
		return "cell";
	}
}

//get list of events
function getDivisionList($all=0)
{
	global $mysqlConn;
	$query = "SELECT * FROM `division` ORDER BY `divisionID` ASC";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output ="<div id='divisionsListDiv'><label for='division'>Division</label> ";
	$output .="<select class='form-select' id='division' name='division' required>";
	if($all)
	{
		$output .="<option value='0'>All Divisions</option>";
	}
	if($result && mysqli_num_rows($result)>0)
	{
		while ($row = $result->fetch_assoc()):
			$output .= "<option value='".$row['divisionID']."'>Division " . $row['divisionID'] . " - ". $row['divisionName'] ."</option>";
		endwhile;
	}
	$output.="</select></div>";
	return $output;
}

//get list of tournaments
function getTeamList($excludeTournament,$labelName='Team')
{
	global $mysqlConn, $schoolID;
	$query = "SELECT `teamID`,`teamName`,`tournamentName`,`dateTournament` FROM `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID` WHERE `schoolID`= $schoolID AND NOT `tournament`.`tournamentID`= $excludeTournament ORDER BY `dateTournament` DESC, `teamName`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output ="<div id='teamsListDiv'><label for='team'>$labelName</label> ";
	$output .="<select class='form-select' id='team' name='team' required>";
	if($result && mysqli_num_rows($result)>0)
	{
		while ($row = $result->fetch_assoc()):
			$output .= "<option value='".$row['teamID']."'>" . $row['tournamentName'] . " - ". $row['teamName'] ." (" . $row['dateTournament']  .")</option>";
		endwhile;
	}
	$output.="</select></div>";
	return $output;
}

//get list of events
function getEventList($number=0,$label)
{
	global $mysqlConn;
	$name = $number>0?"eventsList-$number":"eventsList";
	$query = "SELECT * FROM `event` ORDER BY `event` ASC";
	$resultEventsList = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$events ="<div id='eventsListDiv'><label for='eventsList'>$label</label> ";
	$events .="<select class='form-select' id='$name' name='$name' required>";
	$events .="<option></option>";
	if($resultEventsList)
	{
		while ($row = $resultEventsList->fetch_assoc()):
			$event = htmlspecialchars($mysqlConn->real_escape_string($row['event']));
			$type = getEventString($row['type']);
			$events .= "<option value='".$row['eventID']."'>$event - $type</option>";
		endwhile;
	}
	$events.="</select></div>";
	return $events;
}

//get list of courses
function getCourseList()
{
	global $mysqlConn;
	$query = "SELECT * FROM `course` ORDER BY `course` ASC";// where `field` = $fieldId";
	$resultCourseList = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqldbConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$courses ="<div id='courseListDiv'><label for='courseList'>Courses</label> ";
	$courses .="<select id='courseList' name='courseList' class='form-select'>";
	$courses .="<option></option>";
	if($resultCourseList)
	{
		while ($row = $resultCourseList->fetch_assoc()):
			$courses .= "<option value='" . $row['courseID'] . "'>" . $row['course'] . " - " . $row['level'] . "</option>";
		endwhile;
	}
	$courses.="</select></div>";
	echo $courses;
}

//get list of events for year
function getEventListYear($number,$label, $year, $select)
{
	global $mysqlConn;
	$year = intval($year);
	$query = "SELECT DISTINCT `event`.`eventID`,`event`.`event`,`event`.`type` FROM `event` INNER JOIN `eventyear` ON `event`.`eventID`=`eventyear`.`eventID` WHERE `eventyear`.`year`=$year ORDER BY `event` ASC";
	$resultEventsList = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$events ="<div id='eventsListDiv'><label for='eventsList'>$label</label> ";
	$events .="<select class='form-select' id='eventsList-$number' name='eventsList' required>";
	$events .="<option></option>";
	if($resultEventsList)
	{
		while ($row = $resultEventsList->fetch_assoc()):
			$event = htmlspecialchars($mysqlConn->real_escape_string($row['event']));
			$type = getEventString($row['type']);
			$selected = $row['eventID']==$select?"selected":"";
			$events .= "<option value='".$row['eventID']."' $selected>$event - $type</option>";
		endwhile;
	}
	$events.="</select></div>";
	return $events;
}

//Get all Science Olympiad years from 1982 to current year+1
function getSOYears($myYear,$all=0)
{
	$myYear = isset($myYear) ? $myYear : getCurrentSOYear();
	$output = "<select class='form-select' id='year' name='year' required>";
	if($all)
	{
		$output .="<option value='0'>All Years</option>";
	}
	$i = getCurrentSOYear() + 1;
	for ($i ; $i >= 1982; $i--) {
		$selected = $myYear ==$i ? "selected" : "";
		$output .="<option value='$i' $selected>$i</option>";
	}
	$output .="</select>";
	return $output;
}
//get Current Science Olympiad year
function getCurrentSOYear()
{
	//TODO: Add a way to define a new year on the website for each school
	global $mysqlConn, $schoolID;
	//get current date
	$currentDate = date("Y-m-d");
	//check to see if there is a date in the database
	$query = "SELECT `competitionyear`.`year` FROM `competitionyear` WHERE `competitionyear`.`schoolID`=$schoolID AND `competitionyear`.`startDate`<='$currentDate' AND `competitionyear`.`endDate`>='$currentDate'";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		if($row)
		{
			return $row['year'];
		}
	}

	//if there is not a date set, use default date
	if (date("m")>6)
	{
		return date("Y")+1;
	}
	return date("Y");
}
//get Current Officer Position
function getOfficerPosition($studentID)
{
	global $mysqlConn, $schoolID;
	$year = getCurrentSOYear();
	$query = "SELECT `position` FROM `officer` WHERE `studentID`=$studentID AND `year`=$year";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['position'];
	}
	return "";
}

//get Previous Officer Position List
function getOfficerPositionPrevious($studentID)
{
	$output = "";
	global $mysqlConn, $schoolID;
	$year = getCurrentSOYear();
	$query = "SELECT `year`, `position` FROM `officer` WHERE `studentID`=$studentID AND `year`< $year ORDER BY `year` DESC";
	$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		while ($row = $result->fetch_assoc()):
			$output .= $output ? ", ":"";
			$output .= $row['year']."-".$row['position'];
		endwhile;
	}
	return $output;
}

//get Event Leader Position
function getEventLeaderPosition($studentID)
{
	global $mysqlConn, $schoolID;
	$output = "";
	$year = getCurrentSOYear();
	$query = "SELECT `event` FROM `eventleader` INNER JOIN `event` ON `eventleader`.`eventID` = `event`.`eventID` WHERE `studentID`=$studentID AND `year`=$year";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		while ($row = $result->fetch_assoc()):
			$output .= $output ? ", ":"";
			$output .= $row['event'];
		endwhile;
	}
	return $output;
}

//get Previous Event Leader Position
function getEventLeaderPositionPrevious($studentID,$year)
{
	global $mysqlConn, $schoolID;
	$output = "";
	$year = getIfSet($year,getCurrentSOYear());
	$query = "SELECT `event`,`eventleader`.`year` FROM `eventleader` INNER JOIN `event` ON `eventleader`.`eventID` = `event`.`eventID` WHERE `studentID`=$studentID AND `year`< $year";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		while ($row = $result->fetch_assoc()):
			$output .= $output ? ", ":"";
			$output .= $row['year']."-".$row['event'];
		endwhile;
	}
	return $output;
}

//Get the current event Leader(s) for this school of this event during the selected year
function getEventLeaderIDs($eventID, $year)
{
	global $mysqlConn, $schoolID;
	$yearWhere = "";
	if($year)
	{
		$yearWhere = "AND `eventleader`.`year` = $year";
	}
	$query = "SELECT `student`.`studentID`, `first`, `last`, `year` from `eventleader` INNER JOIN `student` ON `eventleader`.`studentID` = `student`.`studentID`  WHERE `schoolID` = $schoolID AND `eventleader`.`eventID` = $eventID $yearWhere";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = array();
	if($result && $result->num_rows>0){
		while ($row = $result->fetch_assoc()):
			$output[]= $row['studentID'];
		endwhile;
	}
	return $output;
}

//get student's grade from the their graduation years
function getStudentGrade($yearGraduating, $monthGraduating=6)
{
	/*
	if (date("m")>$monthGraduating)
	{
		return 12-($yearGraduating-date("Y")-1);
	}
	else
	{
		return 12-($yearGraduating-date("Y"));
	}*/
	//use CurrentSOYear to determine seniors
	return 12-($yearGraduating-$getCurrentSOYear());
}

//for option htmls
function getSelected($value, $selection)
{
	if($value==getIfSet($selection, 0))
	{
		return "selected";
	}
	else {
		return "";
	}
}

//find student's course enrolled/completed
function getCourses($studentID, $tableName)
{
	global $mysqlConn;
	$myOutput = "";
	$tableID = $tableName . "ID";
	$query = "SELECT * FROM `$tableName` t1 INNER JOIN `course` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=$studentID ORDER BY t2.`course` ASC";// where `field` = $fieldId";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(mysqli_num_rows($result)>0)
	{
		$myOutput .="<div>Course Name - Level</div>";
		while ($row = $result->fetch_assoc()):
			$courseCompleted = "";
			if($tableName == "courseenrolled")
			{
				$courseCompleted = "<a href=\"javascript:studentCourseCompleted('" . $row[$tableID] . "','" . $row['course'] . "')\">Completed</a>";
			}
			$myOutput .= "<div id='$tableName-" . $row[$tableID] . "'><span class='course'>" . $row['course'] . " - " . $row['level'] . " $courseCompleted  </span><a href=\"javascript:studentCourseRemove('" . $row[$tableID] . "','$tableName')\">Remove</a></div>";
		endwhile;
	}
	return $myOutput;
}

//find student's awards
function getAwards($studentID)
{
	global $mysqlConn;
	$output = "";
	$query = "SELECT * FROM `award` WHERE `studentID`=$studentID ORDER BY `awardDate` ASC";// where `field` = $fieldId";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(mysqli_num_rows($result)>0)
	{
		while ($row = $result->fetch_assoc()):
			$output .= "<div id='award-" . $row['awardID'] . "'><span class='award'>" . $row['awardDate'] . " <strong>" . $row['awardName']   . "</strong> ". $row['note'] ."</span><a href=\"javascript:studentAwardEdit('" . $row["awardID"] . "')\">Edit</a></div>";
		endwhile;
	}
	return $output;
}

//find student's courses enrolled but not yet completed
function studentAwards($studentID)
{
	global $mysqlConn;
	$output = "";
	$query = "SELECT * FROM `award` WHERE `studentID`=$studentID ORDER BY `awardDate` ASC";// where `field` = $fieldId";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0)
	{
		$output .="<h3>Awards</h3><ul>";
		while ($row = $result->fetch_assoc()):
			$output .= "<li id='award-" . $row['awardID'] . "'>" . $row['awardDate'] . " - <strong>" . $row['awardName'] . "</strong></li>";
		endwhile;
		$output .= "</ul>";
	}
	return $output;
}

//get tournament
function getTournamentDates()
{

}

//print out privilege editing
function editPrivilege($privilege,$userID)
{
	global $mysqlConn;
	$output = "";
	if($_SESSION['userData']['privilege']>$privilege-1) //student must have the current privilege or higher
	{
		$output .= "<fieldset><legend>Privilege</legend><p>";
		//make an adjustable privilege container for website manager to give higher privileges
		//show privilege
		if(empty($userID))
		{
			$output .= "User has never logged in with registered account.";
		}
		else {
			$query = "SELECT * FROM `user` WHERE `userID`=".$userID;// where `field` = $fieldId";
			$resultPrivilege = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
			$rowPriv = $resultPrivilege->fetch_assoc();
			if ($rowPriv['privilege'])
			{
				$output .= "<label for='privilege'>Privilege</label>";
				$output .= "<input id='privilege' name='privilege' type='text' value='".$rowPriv['privilege']."' onchange='userPrivilege(".$userID.",this.id,this.value)'>";
			}
			else
			{
				$output .=  "User has never logged in with registered account.";
			}
		}
		$output .=  "</p></fieldset>";
	}
	return $output;
}
//Check to make sure google is logged in and set variables
function checkGoogle($gpUserProfile)
{
	global $mysqlConn;
	// Include User library file
	require_once 'user.php';
	// Initialize User class
	$user = new User($mysqlConn);

	// Getting user profile info
	$gpUserData = array();
	$gpUserData['oauth_uid']  = !empty($gpUserProfile['id'])?$gpUserProfile['id']:'';
	$gpUserData['first_name'] = !empty($gpUserProfile['given_name'])?$gpUserProfile['given_name']:'';
	$gpUserData['last_name']  = !empty($gpUserProfile['family_name'])?$gpUserProfile['family_name']:'';
	$gpUserData['email'] = !empty($gpUserProfile['email'])?$gpUserProfile['email']:'';
	$gpUserData['gender'] = !empty($gpUserProfile['gender'])?$gpUserProfile['gender']:'';
	$gpUserData['locale'] = !empty($gpUserProfile['locale'])?$gpUserProfile['locale']:'';
	$gpUserData['picture'] = !empty($gpUserProfile['picture'])?$gpUserProfile['picture']:'';

	// Insert or update user data to the database
	$gpUserData['oauth_provider'] = 'google';
	$userData = $user->checkUser($gpUserData);
	// Storing user data in the session
	$_SESSION['userData'] = $userData;
}

//a much shorter function than previously
//this generates a color in an order for using as a rainbow styling
function rainbow($i) {
	//var colorIncrement = Math.floor(360/colors);
	$light = "60%"; //lightnes of HSL
	$sat = "100%";
	$t=0.3;
	//hue of color
	$n = $i*42;  //pick a number that does not divide evenly into 360, so that the colors don't repeat.
	$hue = $n-floor($n/360)*360; //360 is the highest color, so after 360 the number returns to around zero
	return 'hsla('. $hue .','. $sat .','. $light .','.$t.')';
}

/**
* Generate a random string, using a cryptographically secure
* pseudorandom number generator (random_int)
*
* This function uses type hints now (PHP 7+ only), but it was originally
* written for PHP 5 as well.
*
* For PHP 7, random_int is a PHP core function
* For PHP 5.x, depends on https://github.com/paragonie/random_compat
*
* @param int $length      How many characters do we want?
* @param string $keyspace A string of all possible characters
*                         to select from
* @return string
*/
function random_str(
	int $length = 64,
	string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
	): string {
		if ($length < 1) {
			throw new \RangeException("Length must be a positive integer");
		}
		$pieces = [];
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
			$pieces []= $keyspace[random_int(0, $max)];
		}
		return implode('', $pieces);
	}


	//usage
	/*
	$a = random_str(32);
	$b = random_str(8, 'abcdefghijklmnopqrstuvwxyz');
	$c = random_str();
	*/

	/*
	get a token is not used elsewhere in the table
	*/
	function get_uniqueToken($tableName)
	{
		global $mysqlConn;
		$uniqueToken = random_str(20);
		$query ="SELECT * FROM `$tableName` WHERE `uniqueToken` LIKE '$uniqueToken'";
		$result = $mysqlConn->query($query);
		if ($row = $result->fetch_row()) {
			return get_uniqueToken($tableName);
		} else {
			return $uniqueToken;
		}
	}

	//Remove text with parenthesis.  This is used to remove names in parenthesis for alphabetizing in tournaments.
	function removeParenthesisText($string)
	{
		return preg_replace("/\([^)]+\)/","",$string);
	}

	//Gets the MySQL Current Timestamp.  This allows us to look for changes from this timepoint forward.
	function getCurrentTimestamp()
	{
		global $mysqlConn;
		$query = "SELECT CURRENT_TIMESTAMP(); " ;
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$row = $result->fetch_assoc();
		return $row['CURRENT_TIMESTAMP()'];
	}

	//get home.php carousel for the home schoolID
	function getCarousel()
	{
		global $mysqlConn, $schoolID;
		$output = "";
		//Get student information row information
		$query = "SELECT * FROM `slide` WHERE `schoolID` = $schoolID ORDER BY `slideOrder`";
		$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if($result && mysqli_num_rows($result)>0){
			$output .="<div style='display: block;   margin-left: auto;  margin-right: auto; max-width: 1080px'><div id='homeCarousel' class='carousel slide carousel-dark' data-bs-ride='carousel' style='height:400px;'>";
			$output .="<div class='carousel-indicators'>";
			for ($n = 0; $n < mysqli_num_rows($result); $n++) {
				$active = "";
				if(!$n)
				{
					$active = "class='active' aria-current='true'";
				}
				$output .="<button type='button' data-bs-target='#homeCarousel' data-bs-slide-to='$n' $active aria-label='Slide ". ($n+1) . "'></button>";
			}
			$output .="</div>";
			$output .="<div class='carousel-inner'>";
			$active = "active";
			$interval = "5000";
			while ($row = $result->fetch_assoc()):
				$output .="<div class='carousel-item $active' data-bs-interval='$interval'>";
				$output .="<img src='".$row['image']."' class='d-block w-100' alt='...' style='height:360px;object-fit:cover;'>";
				$output .="<div class='carousel-caption'>";
				$output .= $row['text'];
				$output .="</div>";
				$output .="</div>";
				$active  = "";
				$interval ="3000";
				$n++;

			endwhile;
			$output .="</div>";
			$output .="<button class='carousel-control-prev' type='button' data-bs-target='#homeCarousel' data-bs-slide='prev'>";
			$output .="<span class='carousel-control-prev-icon' aria-hidden='true'></span>";
			$output .="<span class='visually-hidden'>Previous</span>";
			$output .="</button>";
			$output .="<button class='carousel-control-next' type='button' data-bs-target='#homeCarousel' data-bs-slide='next'>";
			$output .="<span class='carousel-control-next-icon' aria-hidden='true'></span>";
			$output .="<span class='visually-hidden'>Next</span>";
			$output .="</button>";
			$output .="</div>";
			$output .="</div>";

		}
		return $output;
	}

	//get home.php carousel for the home schoolID
	function getInfo()
	{
		global $mysqlConn, $schoolID;
		$output = "";
		//Get student information row information
		$query = "SELECT * FROM `news` WHERE `schoolID` = $schoolID";
		$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if($result && mysqli_num_rows($result)>0){
			while ($row = $result->fetch_assoc()):
			$output .=$row['news'];
			endwhile;
		}
		return $output;
	}
	?>
