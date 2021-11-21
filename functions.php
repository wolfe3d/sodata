<?php
//get all students in a select
function getAllStudents($db, $active, $studentID)
{
	$myOutput = "";
	$where = $active==1?"WHERE `student`.`active` = 1":"";
	$query = "SELECT * from `student` $where ORDER BY `last`,`first` ASC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	if($result)
	{
		$myOutput .="<select id='studentID' name='studentID' type='text'>";
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
	$query = "SELECT * from `student` where `userID` = $userID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['studentID'];
	}
	return 0;
}

//get student ID of user
function getStudentName($db, $studentID)
{
	$query = "SELECT * from `student` where `studentID` = $studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['last'] . ", " . $row['first'];
	}
	return 0;
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
	if($editable)
	{
		$href = "href='#tournament-eventnote-$id'";
		$myOutput .="<br>";
		$myOutput .= $noteShort ? "<a $href>$noteShort</a>" : "<a $href>Add</a>";
	}
	else
	{
		$href = "href='#tournament-eventnoteview-$id'";
		$myOutput .= $noteShort ? "<a $href>$noteShort</a>" : "";
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
		$output .="<br><h3>Results</h3>";
		$lasttournament = "";
		while ($row = $result->fetch_assoc()):
			if ($lasttournament !=$row['tournamentID'])
			{
				if ($lasttournament !=0)
				{
					$output.="</ul></div>";
				}
				$output.="<div id=\"".$row['tournamentName']."\">";
				$output.="<h3>".$row['tournamentName']."</h3><ul>";
			}
			//show results
			$output.=	studentEvents($db, $row['tournamentID'], $studentID, true);
			$lasttournament = $row['tournamentID'];
		endwhile;
		$output.="</ul></div>";
	}
	return $output;
}

function studentTournamentSchedule($db, $tournamentID, $studentID)
{
	$schedule="";
    $tournamentQuery = "SELECT DISTINCT `student`.`studentID`, `tournamentevent`.`tournamenteventID`, `teammateplace`.`teamID`,`userID`,`event`.`eventID`, `event`.`event`,`tournamentevent`.`note`,`timeblock`.`timeStart`,`timeblock`.`timeEnd` FROM `teammateplace` INNER JOIN `student` on `teammateplace`.`studentID` = `student`.`studentID` INNER JOIN `tournamentevent` on `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID` inner join `event` on `tournamentevent`.`eventID` = `event`.`eventID` inner join tournamenttimechosen on teammateplace.tournamenteventID = tournamenttimechosen.tournamenteventID inner join timeblock on tournamenttimechosen.timeblockID = timeblock.timeblockID where tournamentevent.`tournamentID` = $tournamentID and `student`.`studentID` = $studentID order by `timeStart`";
	echo $tournamentQuery;
	$tournamentResult = $db->query($tournamentQuery) or print("\n<br />Warning: query failed:$tournamentQuery. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    if($tournamentResult && $tournamentResult->num_rows > 0){
        $schedule.="Your events and partners:<br>";
        $schedule.="<table><tr><th>Time (All times ET)</th><th>Note</th><th>Event</th><th>Partners</th></tr>";
        while ($row = $tournamentResult->fetch_assoc()):
            $tournamenteventID = $row['tournamenteventID'];
            $teamID = $row['teamID'];
            $schedule.="<tr><td>";
            if($row['timeStart']){
				$schedule.=date("H:i",strtotime($row['timeStart']))." - ".date("H:i",strtotime($row['timeEnd']));
            }
            $schedule.="</td><td>".$row['note']."</td>";
			$schedule.="<td>".$row['event']."</td><td>";
            $partnerQuery = "SELECT * FROM `teammateplace` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE `tournamenteventID` = $tournamenteventID and `teamID` = $teamID and `student`.`studentID` != $studentID";
            $partnerResult = $db->query($partnerQuery) or print("\n<br />Warning: query failed:$partnerQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
            if($partnerResult){
                while ($row = $partnerResult->fetch_assoc()):
                    $schedule.=$row['first']." ".$row['last']." ".$row['email']."<br>";
                endwhile;
                $schedule.="</td>";
            }
            $schedule.="</tr>";
        endwhile;
        $schedule.="</table>";
    }
	else{
		return -1;
	}
	return $schedule;
}

//find partners for an event in a tournament
function studentPartners($db,$tournamentEventID, $teamID, $studentID)
{
	//check partner(s)
	$query = "SELECT `first`,`last` FROM `student` INNER JOIN `teammateplace` ON `student`.`studentID`=`teammateplace`.`studentID` WHERE `teammateplace`.`tournamenteventID`=".$tournamentEventID." AND `teammateplace`.`teamID`=".$teamID." AND NOT `teammateplace`.`studentID` = $studentID ORDER BY `student`.`last` ASC, `student`.`first` ASC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output ="";
	$partnerNum = mysqli_num_rows($result);
	if ($partnerNum>0)
	{
		while ($row = $result->fetch_assoc()):
			if($output) $output.=$partnerNum>1 ? ", ":" and "; //adds comma if more than two partners or and to add last partner
			$output.=$row['first']." ".$row['last'];
			$partnerNum -=1;  //reduces partners left to add
		endwhile;
	}
	else
	{
		$output="No partner!";
	}
	return $output;
}

//find Scilympiad ID
function studentScilympiadID($db, $studentID)
{
	$query = "SELECT `scilympiadID` FROM `student` WHERE `student`.`studentID`=$studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
			$output .="<br><h3>Event Priority</h3>";
			while ($row = $result->fetch_assoc()):
				$output .= "<div id='eventPriority-" . $row['eventchoiceID'] . "'>" . $row['year'] . "-" . $row['priority'] . " " . $row['event'] . "</div>";
			endwhile;
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
	$result = $db->query($eventQuery) or error_log("\n<br />Warning: query failed:$eventQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row = $result->fetch_assoc();
	return $row['teamName'];
}

function getTeamEmails($db, $teamID=NULL, $tournamentID=NULL, $parents=false)
{
	$query = "SELECT DISTINCT `first`, `last`, `email`, `parent1First`, `parent1Last`,`parent1Email`,`parent2First`, `parent2Last`,`parent2Email`,`emailSchool`,`tournamentID` FROM `teammate` inner join `student` on `teammate`.`studentID` = `student`.`studentID` inner join `team` on `teammate`.`teamID` = `team`.`teamID` where `active` = 1";
	if($teamID){
		$query.=" and `team`.`teamID` = $teamID";
	}
	if($tournamentID){
		$query.=" and `tournamentID` = $tournamentID";
	}
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$emails = "";
	while ($row = $result->fetch_assoc()):
		if(!$parents){
			if($row['email']){
				$emails.=$row['first'] . " " . $row['last']." &lt;";
				$emails.=$row['email'] . "&gt;; ";
			}
	
			if($row['emailSchool']){
				$emails.="&lt;".$row['emailSchool'] . "&gt;; ";
			}
			$emails.="<br>";
		}
		else
		{
			if($row['parent1Email']){
				$emails.=$row['parent1First'] . " " . $row['parent1Last']." &lt;";
				$emails.=$row['parent1Email'] . "&gt;; ";
			}
			if($row['parent2Email']){
				$emails.=$row['parent2First'] . " " . $row['parent2Last']." &lt;";
				$emails.=$row['parent2Email'] . "&gt;; ";
			}
			$emails.="<br>";
		}
		

	endwhile;
	return $emails;
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
	$resultEventsList = $db->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$events ="<div id='eventsListDiv'><label for='eventsList'>$label</label> ";
	$events .="<select id='eventsList-$number' name='eventsList'>";
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
function getTeamsPrevious($db)
{
	$myOutput = "";
	$query = "SELECT * from `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	if($result)
	{
		$myOutput .="<select id='teamTournament' name='teamTournament' type='text'>";
		while ($row = $result->fetch_assoc()):
			$myOutput.="<option value = '". $row['teamID'] ."'>".$row['tournamentName']." " . $row['teamName'] ."</option>";
		endwhile;
		$myOutput .="</select>";
	}
	return $myOutput;
}
//Get all Science Olympiad years from 1982 to current year+1
function getSOYears($myYear)
{
	$output = "";
	$output .= "<select id='year' name='year'>";
	$i = getCurrentSOYear() + 1;
	for ($i ; $i >= 1982; $i--) {
				$selected = $myYear==$i ? "selected" : "";
				$output .="<option value='$i' $selected>$i</option>";
	}
	$output .="</select>";
	return $output;
}
//get Current Science Olympiad year
function getCurrentSOYear()
{
	if (date("m")>4)
	{
		return date("Y")+1;
	}
	return date("Y");
}
//get Current Officer Position
function getOfficerPosition($db,$studentID)
{
	$year = date("m")>4 ? date("Y")+1 : date("Y");
	$query = "SELECT * FROM `officer` WHERE `studentID`=$studentID AND `year`=$year";
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
	$query = "SELECT * FROM `officer` WHERE `studentID`=$studentID AND `year`< $year ORDER BY `year` DESC";
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
	$query = "SELECT * FROM `eventyear` INNER JOIN `event` ON `eventyear`.`eventID` = `event`.`eventID` WHERE `studentID`=$studentID AND `year`=$year";
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
	$query = "SELECT * FROM `eventyear` INNER JOIN `event` ON `eventyear`.`eventID` = `event`.`eventID` WHERE `studentID`=$studentID AND `year`< $year";
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
function getStudentGrade($yearGraduating, $monthGraduating=5)
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
			$resultPrivilege = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
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
	require_once 'User.class.php';

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

//convert rgb color to hexadecimal
//TODO: possible remove this function.  Currently, deactivated but check all pages to make sure no effect.
/*function rgb($rgb) {
    $ret = '';
    foreach ($rgb as $x) {
        // Make sure the RGB values are 0-255...
        $x = max(0, min(255, $x));
        // create a 2 digit hex value for this color component...
        $ret .= ($x < 16 ? '0'.dechex($x) : dechex($x));
    }
    return '#'.$ret;
}*/

// Returns a color that is part of the rainbow -- not in order of ROYGBIV
function rainbow($i) {
		$opacity = 0.2;
    $rgb = array(255,255,0); //yellow
    // Go through the RGB values and adjust the values by $amount...
		$i = $i - floor($i/11)*11;  //11 is the highest color, so after 11 the number returns to 0

		switch($i) {
			case 1:
				$rgb = array(255,128,0); //orange
				break;
			case 2:
				$rgb = array(255,0,0); //red
				break;
			case 3:
				$rgb = array(255,0,128); //Rose
				break;
			case 4:
				$rgb = array(255,0,255); //Magenta
				break;
			case 5:
				$rgb = array(128,0,255); //violet
				break;
			case 6:
				$rgb = array(0,0,255); //blue
				break;
			case 7:
				$rgb = array(0,128,255); //Azure
				break;
			case 8:
				$rgb = array(0,255,255); //cyan
				break;
			case 9:
				$rgb = array(0,255,128); //Spring Green
				break;
			case 10:
				$rgb = array(0,255,0); //Green
				break;
			case 11:
				$rgb = array(128,255,0); //Chartreuse
				break;
		  default:
		    // code block
		}
    return "rgba(".implode($rgb,",").", $opacity);";
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
?>
