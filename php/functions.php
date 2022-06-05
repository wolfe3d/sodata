<?php
//include file
require_once  ("../connectsodb.php");
require_once 'checksession.php';

//get all students in a select
function getAllStudents($db, $active, $studentID)
{
	$myOutput = "";
	$whereAND = $active==1?"AND `student`.`active` = 1":"";
	$query = "SELECT * from `student` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'] . " $whereAND ORDER BY `last`,`first` ASC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	if($result)
	{
		$myOutput .="<select class='form-select' id='studentID' name='studentID' type='text'>";
		$myOutput.="<option value = '0'>None</option>";
		while ($row = $result->fetch_assoc()):
			$selected = $row['studentID']==$studentID ? " selected " : "";
			$myOutput.="<option value = '".$row['studentID']."'$selected>".$row['last'].", ".$row['first']."</option>";
		endwhile;
		$myOutput .="</select>";
	}
	return $myOutput;
}

//get student ID of user
function getStudentID($db, $userID)
{
	$query = "SELECT `studentID` from `student` where `userID` = $userID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['studentID'];
	}
	return 0;
}
//get coach ID of user
function getCoachID($db, $userID)
{
	$query = "SELECT `coachID` from `coach` where `userID` = $userID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['coachID'];
	}
	return 0;
}

//get Name of user using unique studentID from table.  This is not their school's student id.  Their school's student id is called 'studentschoolID'
function getStudentName($db, $studentID)
{
	$query = "SELECT `last`, `first` from `student` where `studentID` = $studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['last'] . ", " . $row['first'];
	}
	return 0;
}

//get Name of the students school
function getCurrentSchoolName($db)
{
	$query = "SELECT `name`, `division` from `school` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'];
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['name'] . "(" . $row['division'] . ")";
	}
	return 'No school associated';
}

//check Post variables and others if set.
function getIfSet($value, $default = NULL)
{
	return isset($value) ? $value : $default;
}

//post note and link to edit a tournament's event's note
function eventNote($id, $note, $editable = 0)
{
	$myOutput = "";
	$maxLength = 10;
	$noteShort = substr($note, 0, $maxLength);
	$noteShort .= strlen($note)>10 ? "...":"";
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
function studentTournamentResults($db, $studentID)
{
	$query = "SELECT DISTINCT `tournament`.`tournamentID`, `dateTournament`, `tournamentName` FROM `tournament` INNER JOIN `team` ON `tournament`.`tournamentID` = `team`.`tournamentID` INNER JOIN `teammateplace` ON `team`.`teamID` = `teammateplace`.`teamID` WHERE `teammateplace`.`studentID` = $studentID AND `place` IS NOT NULL ORDER BY `dateTournament` DESC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = "";
	if($result && mysqli_num_rows($result)>0)
	{
		$output .="<hr><h3>Results</h3><ul>";
		while ($row = $result->fetch_assoc()):
			$output.="<div id='".$row['tournamentName']."'>";
			$output.="<li>".$row['tournamentName']." - " . $row['dateTournament']. "</li>";
			//show results
			$output.=	studentEvents($db, $row['tournamentID'], $studentID, true);
		endwhile;
		$output.="</ul></div>";
	}
	return $output;
}

//check to see if tournament has assigned any students to team
function tournamentHasTeammates($db, $tournamentID)
{
	$query = "SELECT COUNT(*) FROM `teammate`
	INNER JOIN `student` ON `teammate`.`studentID` = `student`.`studentID`
	INNER JOIN `team` ON `teammate`.`teamID` = `team`.`teamID`
	INNER JOIN `tournament` ON `tournament`.`tournamentID` = `team`.`tournamentID`
	WHERE `tournament`.`tournamentID` = $tournamentID";
	//$query = "SELECT COUNT(*) FROM `teammateplace` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` INNER JOIN `tournamentevent` ON `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID` INNER JOIN `event` ON `tournamentevent`.`eventID` = `event`.`eventID` INNER JOIN `tournamenttimechosen` ON `teammateplace`.`tournamenteventID` = `tournamenttimechosen`.`tournamenteventID` INNER JOIN `timeblock` ON `tournamenttimechosen`.`timeblockID` = `timeblock`.`timeblockID` WHERE `tournamentevent`.`tournamentID` = $tournamentID ORDER BY `timeStart` ";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
function tournamentHasThisTeammate($db, $tournamentID, $studentID)
{
	//Check if this student is on a team
	$query = "SELECT `teamName` FROM `teammate`
	INNER JOIN `student` ON `teammate`.`studentID` = `student`.`studentID`
	INNER JOIN `team` ON `teammate`.`teamID` = `team`.`teamID`
	INNER JOIN `tournament` ON `tournament`.`tournamentID` = `team`.`tournamentID`
	WHERE `tournament`.`tournamentID` = $tournamentID AND `student`.`studentID`= $studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && $result->num_rows > 0)
	{
		$row=$result->fetch_assoc();
		return $row['teamName'];
	}
	return 0;
}

function studentTournamentSchedule($db, $tournamentID, $studentID)
{
	$schedule="";
	//This query checks to see if there are students on this tournaments
	if(tournamentHasTeammates($db, $tournamentID))
	{
		if($teamName = tournamentHasThisTeammate($db, $tournamentID, $studentID))
		{
			$query = "SELECT DISTINCT `student`.`studentID`, `tournamentevent`.`tournamenteventID`, `teammateplace`.`teamID`,`userID`,`event`.`eventID`, `event`.`event`,`tournamentevent`.`note`,`timeblock`.`timeStart`,`timeblock`.`timeEnd` FROM `teammateplace` INNER JOIN `student` on `teammateplace`.`studentID` = `student`.`studentID` INNER JOIN `tournamentevent` on `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID` inner join `event` on `tournamentevent`.`eventID` = `event`.`eventID` inner join tournamenttimechosen on teammateplace.tournamenteventID = tournamenttimechosen.tournamenteventID inner join timeblock on tournamenttimechosen.timeblockID = timeblock.timeblockID where tournamentevent.`tournamentID` = $tournamentID AND `student`.`studentID` = $studentID order by `timeStart`";
			$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
			if($result && $result->num_rows > 0){
				$schedule.="<h4>Your events and partners</h4>";
				$schedule.="<table class='table table-hover table-striped'><thead class='table-dark'><tr><th>Time (All times ET)</th><th>Event</th><th>Note</th><th>Partners</th></tr></thead><tbody>";
				while ($row = $result->fetch_assoc()):
					$schedule.="<tr><td>";
					if($row['timeStart']){
						$schedule.=date("H:i",strtotime($row['timeStart']))." - ".date("H:i",strtotime($row['timeEnd']));
					}
					$schedule.="</td>";
					$schedule.="<td>".$row['event']."</td>";
					$schedule.="<td>".$row['note']."</td>";
					$schedule.="<td>".studentPartnersWithEmails($db,$row['tournamenteventID'], $row['teamID'], $studentID)."</td>";
					$schedule.="</tr>";
				endwhile;
				$schedule.="</tbody></table>";
			}
			else {
				return "<div class='text-warning'>SAVE THE DATE.  You have been assigned to Team $teamName, but you have not been assigned events yet.</div>";
			}
		}
		else {
			return "<div class='text-warning'>SAVE THE DATE.  You have NOT been assigned to this team, but you may be added later.  Contact Coach/Leader if you cannot make it.</div>";
		}
	}
	else {
		return "<div class='text-warning'>SAVE THE DATE.  Students have not been scheduled for this tournament.</div>";
	}
	return $schedule;
}

//get latest team schedule - also known as the notCompetition Tournament.
function getLatestTeamTournamentStudent($db, $userID, $studentID)
{
	$query = "SELECT DISTINCT `tournament`.`tournamentID`, `dateTournament`, `tournamentName` FROM `tournament` INNER JOIN `team` ON `tournament`.`tournamentID` = `team`.`tournamentID` INNER JOIN `teammateplace` ON `team`.`teamID` = `teammateplace`.`teamID` WHERE `teammateplace`.`studentID` = $studentID AND `notCompetition`=1 ORDER BY `dateTournament` DESC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = "";
	if($result && mysqli_num_rows($result)>0)
	{
		$row = $result->fetch_assoc();
		$output.="<hr><div id='".$row['tournamentName']."'>";
		$output .="<h3>".$row['tournamentName']." - " . $row['dateTournament']. "</h3>";
		$output.="<div><a class='btn btn-primary' role='button' href=\"#tournament-view-".$row['tournamentID']."\"><span class='fa fa-desktop'></span> View Details</a></div>";
		$output.=	studentEvents($db, $row['tournamentID'], $studentID, false);
		$output.="</div>";
	}
	return $output;
}

//get upcoming tournament Information for Students
function getUpcomingTournamentStudent($db, $userID, $studentID)
{
	$date = date('Y-m-d', time());
	$query = "SELECT `tournamentName`,`tournamentID`,`dateTournament`,`tournament`.`schoolID` FROM `student` INNER JOIN `tournament` ON `tournament`.`schoolID` = `student`.`schoolID` WHERE `studentID` = $studentID AND `dateTournament` >= '$date' AND `notCompetition` = 0 ORDER BY `dateTournament`";
	//$query = "SELECT `tournamentName`,`tournament`.`tournamentID`,`dateTournament`,`teamName` FROM `student` INNER JOIN `teammate` ON `student`.`studentID`=`teammate`.`studentID` INNER JOIN `team` ON `teammate`.`teamID` = `team`.`teamID` INNER JOIN `tournament` ON `team`.`tournamentID` = `tournament`.`tournamentID` WHERE `userID` = $userID AND `dateTournament` >= '$date' AND `notCompetition` = 0 ORDER BY `dateTournament`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = '';
	if($result && mysqli_num_rows($result)>0)
	{
		$output = '<hr><h2>Upcoming Tournaments</h2>';
		while ($row = $result->fetch_assoc()):
			$output.="<div id=\"".$row['tournamentName']."\">";
			$output.="<h3>".$row['tournamentName']." - ".$row['dateTournament'] . "</h3>";
			$output.="<div><a class='btn btn-primary' role='button' href=\"#tournament-view-".$row['tournamentID']."\"><span class='fa fa-desktop'></span> View Details</a></div>";
			$output.=studentTournamentSchedule($db, $row['tournamentID'], $studentID);
			$output.="</div>";
		endwhile;
	}
	return $output;
}

//get upcoming tournament Information for Coaches
function getUpcomingTournamentCoach($db, $schoolID)
{
	$date = date('Y-m-d', time());
	//fallRosterDate should be changed to a part of the table that indicated that this is a roster (not a tournament)
	$query = "SELECT `tournamentName`,`tournamentID`,`dateTournament` FROM `tournament` WHERE `schoolID` = $schoolID AND `dateTournament` >= '$date' AND `notCompetition` = 0 ORDER BY `dateTournament`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = '';
	if($result)
	{
		$output = '<h2>Upcoming Tournaments</h2><ul>';
		while ($row = $result->fetch_assoc()):
			$output.="<li id=\"".$row['tournamentName']."\">";
			$output.= "<a class='btn btn-primary' role='button' href=\"#tournament-view-".$row['tournamentID']."\"><span class='fa fa-desktop'></span> ".$row['tournamentName']."</a>";
			$output .= " - " . $row['dateTournament'];
			$output.="</li>";
		endwhile;
		$output .= '</ul>';
	}
	return $output;
}

//find partners for an event in a tournament
function studentPartners($db,$tournamentEventID, $teamID, $studentID)
{
	//check partner(s)
	$query = "SELECT `first`,`last` FROM `student` INNER JOIN `teammateplace` ON `student`.`studentID`=`teammateplace`.`studentID` WHERE `teammateplace`.`tournamenteventID`=".$tournamentEventID." AND `teammateplace`.`teamID`=".$teamID." AND NOT `teammateplace`.`studentID` = $studentID ORDER BY `student`.`last` ASC, `student`.`first` ASC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
function studentPartnersWithEmails($db,$tournamentEventID, $teamID, $studentID)
{
	//check partner(s)
	$output =  "";
	$query = "SELECT * FROM `teammateplace` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE `tournamenteventID` = $tournamentEventID and `teamID` = $teamID and `student`.`studentID` != $studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0){
		while ($row = $result->fetch_assoc()):
			$output.= $row['first']." ".$row['last']." <a href='mailto:".$row['email']."'>".$row['email']."</a><br>";
		endwhile;
	}
	else {
		$output = "No partner!";
	}
	return $output;
}

//find Scilympiad ID
function studentScilympiadID($db, $studentID)
{
	$query = "SELECT `scilympiadID` FROM `student` WHERE `student`.`studentID`=$studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output ="";
	if ($row = $result->fetch_assoc())
	{
		$output=$row['scilympiadID']?$row['scilympiadID']:"Not set";
	}
	return $output;
}

//find student courses completed
function studentCourseCompleted($db, $studentID)
{
	$output = "";
	$query = "SELECT * FROM `coursecompleted` t1 INNER JOIN `course` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=$studentID ORDER BY t2.`course` ASC";// where `field` = $fieldId";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0)
	{
		$output .="<br><h3>Courses Completed - Level</h3>";
		while ($row = $result->fetch_assoc()):
			$output .= "<div id='courseCompleted-" . $row['coursecompletedID'] . "'>" . $row['course'] . " - " . $row['level'] . "</div>";
		endwhile;
	}
	return $output;
}


//find student's courses enrolled but not yet completed
function studentCourseEnrolled($db, $studentID)
{
	$output = "";
	$query = "SELECT * FROM `courseenrolled` t1 INNER JOIN `course` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=$studentID ORDER BY t2.`course` ASC";// where `field` = $fieldId";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0)
	{
		$output .="<br><h3>Courses Enrolled - Level</h3>";
		while ($row = $result->fetch_assoc()):
			$output .= "<div id='courseEnrolled-" . $row['courseenrolledID'] . "'>" . $row['course'] . " - " . $row['level'] . "</div>";
		endwhile;
	}
	return $output;
}

//find student's event priority
function studentEventPriority($db, $studentID)
{
	$output = "";
	$query = "SELECT * FROM `eventchoice` INNER JOIN `eventyear` ON `eventchoice`.`eventyearID`=`eventyear`.`eventyearID` INNER JOIN `event` ON `eventyear`.`eventID`=`event`.`eventID` WHERE `eventchoice`.`studentID`=$studentID ORDER BY `eventyear`.`year` DESC, `eventchoice`.`priority` ASC";// where `field` = $fieldId";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
function studentEvents($db, $tournamentID, $studentID, $showPlace)
{
	$eventQuery = "SELECT `teammateplace`.`tournamenteventID`, `teamID`, `event`, `tournamentevent`.`eventID`, `place` FROM `teammateplace` INNER JOIN `student` on `teammateplace`.`studentID` = `student`.`studentID` INNER JOIN `tournamentevent` on `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID` inner join `event` on `tournamentevent`.`eventID` = `event`.`eventID` where `tournamentID` = $tournamentID and `student`.`studentID` = $studentID";
	$result = $db->query($eventQuery) or error_log("\n<br />Warning: query failed:$eventQuery. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = "<ul>";
	if ($result && mysqli_num_rows($result)>0)
	{
		while ($row = $result->fetch_assoc()):
			//show results
			$output.="<li>".$row['event'];
			if($showPlace)
			{
				$output.=": ". $row['place'];
			}
			$output.=" (".studentPartners($db, $row['tournamenteventID'], $row['teamID'], $studentID).")</li>";
		endwhile;
	}
	$output .= "</ul>";
	return $output;
}

//get student team name from a spceific tournament
function getStudentTeam($db, $tournamentID, $studentID)
{
	$eventQuery = "SELECT DISTINCT `teamName` FROM `teammateplace` inner join `student` on `teammateplace`.`studentID` = student.studentID inner join team on teammateplace.teamID = team.teamID where tournamentID = $tournamentID and student.studentID = $studentID";
	$result = $db->query($eventQuery) or error_log("\n<br />Warning: query failed:$eventQuery. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row = $result->fetch_assoc();
	return $row['teamName'];
}

//returns a bare list of names with multiple emails
function getEmailList($result)
{
	$emails = "";
	while ($row = $result->fetch_assoc()):
		$emails.=$row['first'] . " " . $row['last'] . " ";
		echo $emails;
		if(isset($row['email'])&&$row['email']){
			$emails.= "&lt;" . $row['email'] . "&gt;; ";
		}

		if(isset($row['emailSchool'])&&$row['emailSchool']){
			$emails.="&lt;".$row['emailSchool'] . "&gt;; ";
		}
		$emails.="<br>";
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
		}
		if($row['parent2Email']){
			$emails.=$row['parent2First'] . " " . $row['parent2Last']." ";
			$emails.="&lt;" . $row['parent2Email'] . "&gt;; ";
		}
		$emails.="<br>";
	endwhile;
	return $emails;
}

//Return Coaches leader email list
function getCoachesEmails($db, $year)
{
	//$year = isset($year)?$year:getCurrentSOYear(); //assumes $year is an integer
	$query = "SELECT DISTINCT `first`, `last`, `emailSchool` FROM `coach` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'];
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	return getEmailList($result);
}

//Get Team Emails either students or parents
function getTeamEmails($db, $teamID=NULL, $tournamentID=NULL, $parents=false)
{
	$query = "SELECT DISTINCT `first`, `last`, `email`, `parent1First`, `parent1Last`,`parent1Email`,`parent2First`, `parent2Last`,`parent2Email`,`emailSchool`,`tournamentID` FROM `teammate` inner join `student` on `teammate`.`studentID` = `student`.`studentID` inner join `team` on `teammate`.`teamID` = `team`.`teamID` where `active` = 1";
	if($teamID){
		$query.=" AND `team`.`teamID` = $teamID";
	}
	if($tournamentID){
		$query.=" AND `tournamentID` = $tournamentID";
	}
	echo $query;
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$emails = "";
	if(!$parents){
		$emails = getEmailList($result);
	}
	else
	{
		echo "here";
		$emails = getEmailParentList($result);
	}
	echo "here";
	return $emails. getCoachesEmails($db, NULL);
}


//Return officer leader email list
function getOfficerEmails($db, $year)
{
	$year = isset($year)?$year:getCurrentSOYear(); //assumes $year is an integer

	$query = "SELECT DISTINCT `first`, `last`, `email`, `parent1First`, `parent1Last`,`parent1Email`,`parent2First`, `parent2Last`,`parent2Email`,`emailSchool` FROM `officer` INNER JOIN `student` ON `officer`.`studentID`= `student`.`studentID` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'] . " AND `year`=$year";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	return getEmailList($result) . getCoachesEmails($db, $year);
}

//Return event leader email list
function getLeaderEmails($db, $year)
{
	$year = isset($year)?$year:getCurrentSOYear(); //assumes $year is an integer
	$query = "SELECT DISTINCT `first`, `last`, `email`, `parent1First`, `parent1Last`,`parent1Email`,`parent2First`, `parent2Last`,`parent2Email`,`emailSchool` FROM `eventyear` INNER JOIN `student` ON `eventyear`.`studentID`= `student`.`studentID` INNER JOIN `event` ON `eventyear`.`eventID`=`event`.`eventID` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'] . " AND `year`=$year";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] . ".");
	return getEmailList($result) . getCoachesEmails($db, $year);
	}

	//Return all active students or parents
	function getStudentEmails($db, $year, $parents=false)
	{
	//$year = isset($year)?$year:getCurrentSOYear(); //assumes $year is an integer
	$query = "SELECT DISTINCT `first`, `last`, `email`, `parent1First`, `parent1Last`,`parent1Email`,`parent2First`, `parent2Last`,`parent2Email`,`emailSchool` FROM `student` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'] . " AND `active`=1";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$emails = "";
	if(!$parents){
		$emails = getEmailList($result);
	}
	else
	{
		$emails = getEmailParentList($result);
	}
	return $emails. getCoachesEmails($db, NULL);
}


//find the name of the event
function getEventName($db,$eventID)
{
	$query = "SELECT `event` FROM `event` WHERE `eventID`=$eventID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
function getEventList($db, $number,$label)
{
	$query = "SELECT * FROM `event` ORDER BY `event` ASC";
	$resultEventsList = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$events ="<div id='eventsListDiv'><label for='eventsList'>$label</label> ";
	$events .="<select class='form-select' id='eventsList-$number' name='eventsList'>";
	if($resultEventsList)
	{
		while ($rowEvents = $resultEventsList->fetch_assoc()):
			$event = htmlspecialchars($db->real_escape_string($rowEvents['event']));
			$type = getEventString($rowEvents['type']);
			$events .= "<option value='".$rowEvents['eventID']."'>$event - $type</option>";
		endwhile;
	}
	$events.="</select></div>";
	return $events;
}

//get Teams from previous tournaments
function getTeamsPrevious($db, $tournamentID)
{
	$myOutput = "";
	$query = "SELECT * from `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID` WHERE `team`.`tournamentID`!= $tournamentID ORDER BY `dateTournament` DESC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	if($result)
	{
		$myOutput .="<select class='form-select' id='teamTournament' name='teamTournament' type='text'>";
		while ($row = $result->fetch_assoc()):
			$myOutput.="<option value = '". $row['teamID'] ."'>".$row['year']." ".$row['tournamentName']." " . $row['teamName'] ."</option>";
		endwhile;
		$myOutput .="</select>";
	}
	return $myOutput;
}
//Get all Science Olympiad years from 1982 to current year+1
function getSOYears($myYear,$all=0)
{
	$myYear = isset($myYear) ? $myYear : getCurrentSOYear();
	$output = "<select class='form-select' id='year' name='year'>";
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
	if (date("m")>6)
	{
		return date("Y")+1;
	}
	return date("Y");
}
//get Current Officer Position
function getOfficerPosition($db,$studentID)
{
	$year = date("m")>4 ? date("Y")+1 : date("Y");
	$query = "SELECT `position` FROM `officer` WHERE `studentID`=$studentID AND `year`=$year";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['position'];
	}
	return "";
}

//get Previous Officer Position List
function getOfficerPositionPrevious($db,$studentID)
{
	$output = "";
	$year = date("m")>4 ? date("Y")+1 : date("Y");
	$query = "SELECT `year`, `position` FROM `officer` WHERE `studentID`=$studentID AND `year`< $year ORDER BY `year` DESC";
	$result = $db->query($query) or print("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		while ($row = $result->fetch_assoc()):
			$output .= $output ? ", ":"";
			$output .= $row['year']."-".$row['position'];
		endwhile;
	}
	return $output;
}
//get Current Event Leader Position
function getEventLeaderPosition($db,$studentID)
{
	$output = "";
	$year = date("m")>4 ? date("Y")+1 : date("Y");
	$query = "SELECT `event` FROM `eventyear` INNER JOIN `event` ON `eventyear`.`eventID` = `event`.`eventID` WHERE `studentID`=$studentID AND `year`=$year";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		while ($row = $result->fetch_assoc()):
			$output .= $output ? ", ":"";
			$output .= $row['event'];
		endwhile;
	}
	return $output;
}

//get Current Event Leader Position
function getEventLeaderPositionPrevious($db,$studentID)
{
	$output = "";
	$year = date("m")>4 ? date("Y")+1 : date("Y");
	$query = "SELECT `year`, `event` FROM `eventyear` INNER JOIN `event` ON `eventyear`.`eventID` = `event`.`eventID` WHERE `studentID`=$studentID AND `year`< $year";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		while ($row = $result->fetch_assoc()):
			$output .= $output ? ", ":"";
			$output .= $row['year']."-".$row['event'];
		endwhile;
	}
	return $output;
}

//get student's grade from the their graduation years
function getStudentGrade($yearGraduating, $monthGraduating=6)
{
	if (date("m")>$monthGraduating)
	{
		return 12-($yearGraduating-date("Y")-1);
	}
	else
	{
		return 12-($yearGraduating-date("Y"));
	}
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
function getCourses($db, $studentID, $tableName)
{
	$myOutput = "";
	$tableID = $tableName . "ID";
	$query = "SELECT * FROM `$tableName` t1 INNER JOIN `course` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=$studentID ORDER BY t2.`course` ASC";// where `field` = $fieldId";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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

//get tournament
function getTournamentDates()
{

}

//print out privilege editing
function editPrivilege($privilege,$userID,$db)
{
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
			$resultPrivilege = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
function checkGoogle($gpUserProfile,$db)
{
	// Include User library file
	require_once 'user.php';
	// Initialize User class
	$user = new User($db);

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
	function get_uniqueToken($db, $tableName)
	{
		$uniqueToken = random_str(20);
		$query ="SELECT * FROM `$tableName` WHERE `uniqueToken` LIKE '$uniqueToken'";
		echo $query;
		$result = $db->query($query);
		if ($row = $result->fetch_row()) {
			return get_uniqueToken($db,$tableName);
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
	function getCurrentTimestamp($db)
	{
		$query = "SELECT CURRENT_TIMESTAMP(); " ;
		$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$row = $result->fetch_assoc();
		return $row['CURRENT_TIMESTAMP()'];
	}
	?>