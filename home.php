<?php
require_once("php/functions.php");
userCheckPrivilege(1);
$output = "";
$userID = $_SESSION['userData']['userID'];

//get upcoming tournament Information for Students
function getUpcomingTournamentStudent($userID, $studentID)
{
	global $mysqlConn;
	$date = date('Ymd', time());
	$query = "SELECT `tournamentName`,`tournamentID`,`dateTournament`,`tournament`.`schoolID`, `tournament`.`year`
	FROM `student` INNER JOIN `tournament` ON `tournament`.`schoolID` = `student`.`schoolID`
	WHERE `studentID` = $studentID AND `dateTournament` >= '$date' AND `notCompetition` = 0
	ORDER BY `dateTournament`";
	//$query = "SELECT `tournamentName`,`tournament`.`tournamentID`,`dateTournament`,`teamName` FROM `student` INNER JOIN `teammate` ON `student`.`studentID`=`teammate`.`studentID` INNER JOIN `team` ON `teammate`.`teamID` = `team`.`teamID` INNER JOIN `tournament` ON `team`.`tournamentID` = `tournament`.`tournamentID` WHERE `userID` = $userID AND `dateTournament` >= '$date' AND `notCompetition` = 0 ORDER BY `dateTournament`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = '';
	if($result && mysqli_num_rows($result)>0)
	{
		$output = '<hr><h2>Upcoming Tournaments</h2>';
		while ($row = $result->fetch_assoc()):
			$output.="<div id=\"".$row['tournamentName']."\">";
			$output .= "<div style='display: flex; align-items: center; gap: 1rem;'>";
			$output .= "<h3>".$row['tournamentName']." - ".$row['dateTournament'] . "</h3>";
			$output .= "<div class='d-block d-md-none'>
							<a class='btn btn-primary btn-sm' role='button' href='#tournament-view-".$row['tournamentID']."'>
								<span class='bi bi-controller'></span> View Details
							</a>
						</div>
						<div class='d-none d-md-block'>
							<a class='btn btn-primary' role='button' href='#tournament-view-".$row['tournamentID']."'>
								<span class='bi bi-controller'></span> View Details
							</a>
						</div>";
			$output .= "</div>";
			$output.=studentTournamentSchedule($row['tournamentID'], $studentID, "", $row['year']);
			$output.="</div>";
		endwhile;
	}
	return $output;
}
//get upcoming tournament Information for Coaches
function getUpcomingTournamentCoach()
{
	global $mysqlConn, $schoolID;
	$date = date('Ymd', time());
	//fallRosterDate should be changed to a part of the table that indicated that this is a roster (not a tournament)
	$query = "SELECT `tournamentName`,`tournamentID`,`dateTournament` FROM `tournament`
	WHERE `schoolID` = $schoolID AND `dateTournament` >= '$date' ORDER BY `dateTournament`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = '';
	if($result && mysqli_num_rows($result)>0)
	{
		$output = '<h2>Upcoming Tournaments</h2><ul>';
		while ($row = $result->fetch_assoc()):
			$output.="<li id=\"".$row['tournamentName']."\">";
			$output.= "<a class='btn btn-primary btn-sm' role='button' href=\"#tournament-view-".$row['tournamentID']."\"><span class='bi bi-controller'></span> ".$row['tournamentName']."</a>";
			$output .= " - " . $row['dateTournament'];
			$output.="</li>";
		endwhile;
		$output .= '</ul>';
	}
	return $output;
}


// Get all meetings from an event
function getEventMeetings($eventID)
{
    global $mysqlConn;
    $query = "SELECT * FROM `meeting` WHERE `meeting`.`eventID` = $eventID AND `meeting`.`meetingTypeID` = 1 ORDER BY `meeting`.`meetingDate` DESC";
    $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    $output = '';
    if($result && mysqli_num_rows($result)>0)
    {
        while ($row = $result->fetch_assoc()):
            $output .= "<div class='mb-3'>";
            $output .= "<strong id=" . $row['meetingID'] . ">" . $row['meetingDate'] . "</strong>";
            $output .= "<div>Description: " . $row['meetingDescription'] . "</div>";
            $output .= "<div>Homework: " . $row['meetingHW'] . "</div>";
            $output .= "</div>";
        endwhile;
    }
    return $output;
}
//Just Get Most Recent Event Meeting Date (Optimizable)
function getEventMeetingDate($eventID)
{
    global $mysqlConn;
    $query = "SELECT * FROM `meeting` WHERE `meeting`.`eventID` = $eventID ORDER BY `meeting`.`meetingDate` LIMIT 1";
    $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    $output = '';
	if (mysqli_num_rows($result)>0)
	{
		$row = $result->fetch_assoc();
		$output = $row['meetingDate'];
	}
    return $output;
}
// Get all general meetings
function getGeneralMeetings()
{
	global $mysqlConn;
    $query = "SELECT * FROM `meeting` WHERE `meeting`.`meetingTypeID` = 2 ORDER BY `meeting`.`meetingDate` DESC";
    $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    $output = '';
    if($result && mysqli_num_rows($result)>0)
    {
        while ($row = $result->fetch_assoc()):
            $output .= "<div class='mb-3'>";
            $output .= "<strong id=" . $row['meetingID'] . ">" . $row['meetingDate'] . "</strong>";
            $output .= "<div>Description: " . $row['meetingDescription'] . "</div>";
            $output .= "</div>";
        endwhile;
    }
    return $output;
}
// Get all event leader meetings
function getEventLeaderMeetings()
{
	global $mysqlConn;
    $query = "SELECT * FROM `meeting` WHERE `meeting`.`meetingTypeID` = 4 ORDER BY `meeting`.`meetingDate` DESC";
    $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    $output = '';
    if($result && mysqli_num_rows($result)>0)
    {
        while ($row = $result->fetch_assoc()):
            $output .= "<div class='mb-3'>";
            $output .= "<strong id=" . $row['meetingID'] . ">" . $row['meetingDate'] . "</strong>";
            $output .= "<div>Description: " . $row['meetingDescription'] . "</div>";
            $output .= "</div>";
        endwhile;
    }
    return $output;
}
// Get all officer meetings
function getOfficerMeetings()
{
	global $mysqlConn;
    $query = "SELECT * FROM `meeting` WHERE `meeting`.`meetingTypeID` = 3 ORDER BY `meeting`.`meetingDate` DESC";
    $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    $output = '';
    if($result && mysqli_num_rows($result)>0)
    {
        while ($row = $result->fetch_assoc()):
            $output .= "<div class='mb-3'>";
            $output .= "<strong id=" . $row['meetingID'] . ">" . $row['meetingDate'] . "</strong>";
            $output .= "<div>Description: " . $row['meetingDescription'] . "</div>";
            $output .= "</div>";
        endwhile;
    }
    return $output;
}

// Get all event meetings for a student
function getEventMeetingsByStudent($tournamentID, $studentID)
{
	global $mysqlConn;
	$eventQuery = "SELECT `event`, `tournamentevent`.`eventID` 
	FROM `teammateplace` 
	INNER JOIN `student` on `teammateplace`.`studentID` = `student`.`studentID` 
	INNER JOIN `tournamentevent` on `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID` 
	INNER JOIN `event` on `tournamentevent`.`eventID` = `event`.`eventID` where `tournamentID` = $tournamentID and `student`.`studentID` = $studentID 
	ORDER BY `event`.`event`";
	$result = $mysqlConn->query($eventQuery) or error_log("\n<br />Warning: query failed:$eventQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output = "";
	if ($result && mysqli_num_rows($result)>0)
	{
		while ($row = $result->fetch_assoc()):
			$eventName = $row['event'];
			$eventID = $row['eventID'];
			$output .= "
			<div class='accordion-item'>
				<h2 class='accordion-header' id='heading-eventID-{$eventID}'>
					<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse-eventID-{$eventID}' aria-expanded='false' aria-controls='collapse-eventID-{$eventID}'>
						{$eventName}
					</button>
				</h2>
				<div id='collapse-eventID-{$eventID}' class='accordion-collapse collapse' aria-labelledby='heading-eventID-{$eventID}' data-bs-parent='#meetingsAccordion'>
					<div class='accordion-body'>
						" . getEventMeetings($eventID) . "
					</div>
				</div>
			</div>";
			
		endwhile;
	}
	return $output;
}

//get all officer meetings for a student
function getOfficerMeetingsByStudent()
{
	$output = "
		<div class='accordion-item'>
			<h2 class='accordion-header' id='headingOfficer'>
				<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseOfficer' aria-expanded='false' aria-controls='collapseOfficer'>
					Officer Meetings
				</button>
			</h2>
			<div id='collapseOfficer' class='accordion-collapse collapse' aria-labelledby='headingOfficer' data-bs-parent='#meetingsAccordion'>
				<div class='accordion-body'>
					" . getOfficerMeetings() . "
				</div>
			</div>
		</div>";
	return $output;
}

//Output general meetings
function getStudentGeneralMeetings($studentID)
{
    global $mysqlConn;

    $query = "SELECT DISTINCT `tournament`.`tournamentID`, `dateTournament`, `tournamentName`, `teamName`
	FROM `tournament` INNER JOIN `team` ON `tournament`.`tournamentID` = `team`.`tournamentID`
	INNER JOIN `teammateplace` ON `team`.`teamID` = `teammateplace`.`teamID`
	WHERE `teammateplace`.`studentID` = $studentID AND `notCompetition`=1 AND `published`=1 
	ORDER BY `dateTournament` DESC
    LIMIT 1";

    $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    
    $output = "";
    if ($result && mysqli_num_rows($result) > 0) {
        $output .= "<div class='accordion' id='meetingsAccordion'>";
        
        while ($row = $result->fetch_assoc()) {
            $tournamentID = $row['tournamentID'];
            $tournamentName = $row['tournamentName'];
            $dateTournament = $row['dateTournament'];
            $output .= getGeneralMeetingsByStudent($tournamentID, $studentID, $dateTournament);
        }

        $output .= "</div>";
    }
    return $output;
}


if(!empty($_SESSION['userData'])){
	$studentID = NULL;
	$coachID = NULL;
	if($_SESSION['userData']['type'] =='student')
	{
		$studentID = getStudentID($userID);
	}
	else if ($_SESSION['userData']['type'] )
	{
		$coachID = getCoachID($userID);
	}
	$output .= '<p>You are logged in to Walton Science Olympiad Team Website!</p>';

	$output .= "<div style='display: flex; justify-content: center; vertical-align: middle;padding: 10px;'><img style='vertical-align: middle' height='256px' src='images/waltoncthulu256.png'></img></div>";
	$output .= '<div>';

	$output .= getCarousel();
	if(userHasPrivilege(4))
	{
		$output .= "<p><a type='button' class='btn btn-primary' href='#home-edit'><span class='bi bi-edit'></span> Edit Carousel</button></a></p>";
	}
	$output .= getInfo();
	if(userHasPrivilege(4))
	{
		$output .= "<p><a type='button' class='btn btn-primary' href='#news-edit'><span class='bi bi-edit'></span> Edit News</button></a></p>";
	}

	$output .= "<hr><h2>My Profile</h2>";
	$output .= '<div style="display: flex; align-items: center; gap: 24px;">';
	$output .= '<img class="d-none d-md-block" src="'.$_SESSION['userData']['picture'].'" style="width:130px; height:130px; border-radius:50%;">';
	$output .= '<div>';
	$output .= '<h5><b>Name:</b> '.$_SESSION['userData']['first_name'].' '.$_SESSION['userData']['last_name'].' | '.$_SESSION['userData']['email'].'</h5>';
	
	if($studentID)
	{
		$output .= "<h5><a href='https://scilympiad.com/public/Student/StudentDB'><b>Scilympiad</a> ID</b>: ".studentScilympiadID($studentID)."</h5>";
		$output.="<p><a class='btn btn-info' role='button' href='#student-details-$studentID'><span class='bi bi-file-earmark-person'></span> Full Student Information</a></p>";
	}
	$output .= "</ul>";
	$output .= '</div>';
	$output .= '</div>';

	//Reminders
	//Show new tournaments signups with links to tournament pages, priority of events with links to events, previous tournament results.
	if($studentID)
	{
		$output .=	getUpcomingTournamentStudent($userID, $studentID);
	}
	else if($coachID){
		$output .=	getUpcomingTournamentCoach();
	}

	if($studentID)
	{
		//Get latest team assignments
		$teamRow = getLatestTeamTournamentStudentRow($studentID);
		$myEvents = printLatestTeamTournamentStudent($studentID, $teamRow);
		//show student's event priority
		//$myEvents .= studentEventPriority($studentID);
		if($myEvents)
		{
			$output .= "<hr><h2>My Events</h2>" . $myEvents;
		}
		$output .= "<hr><h2>My Meetings</h2>";
		$output .= "<h3>Event Meetings</h3>";
    	$output .= "<div class='accordion' id='meetingsEventMeetingsAccordion'>";
		$output .= getEventMeetingsByStudent($teamRow['tournamentID'], $studentID);
    	$output .= "</div>";

		$output .= "<br>";
	
		
	}
	if($studentID||$coachID)
	{
		$output .= "<h3>Other Meetings</h3>";
		//General Meetings
		$output .= "<div class='accordion' id='meetingsGeneralMeetingAccordion'>";
		$output .= "
			<div class='accordion-item'>
				<h2 class='accordion-header' id='headingGeneral'>
					<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseGeneral' aria-expanded='false' aria-controls='collapseGeneral'>
						General Meetings
					</button>
				</h2>
				<div id='collapseGeneral' class='accordion-collapse collapse' aria-labelledby='headingGeneral' data-bs-parent='#meetingsAccordion'>
					<div class='accordion-body'>
						" . getGeneralMeetings() . "
					</div>
				</div>
			</div>";
		$output .= "</div>";
		//print event leader meetings
		if (userHasPrivilege(2)) {
			$output .= "<div class='accordion' id='meetingsEventLeaderAccordion'>";
			$output .= "
			<div class='accordion-item'>
				<h2 class='accordion-header' id='headingEventLeader'>
					<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseEventLeader' aria-expanded='false' aria-controls='collapseEventLeader'>
						Event Leader Meetings
					</button>
				</h2>
				<div id='collapseEventLeader' class='accordion-collapse collapse' aria-labelledby='headingEventLeader' data-bs-parent='#meetingsAccordion'>
					<div class='accordion-body'>
						" . getEventLeaderMeetings() . "
					</div>
				</div>
			</div>";
			$output .= "</div>";
		}
		//print Officer meetings
		if (userHasPrivilege(3)) {
			$output .= "<div class='accordion' id='meetingsOfficerAccordion'>";
        	$output .= getOfficerMeetingsByStudent();
        	$output .= "</div>";
		}
	}
	if($studentID)
	{
		//show all previous results for this student
		$myTournamentResults = studentTournamentResultsAccordion($studentID, true);
		$myAwards = studentAwards($studentID);

		if($myTournamentResults)
		{
			$output .= "<hr><h2>My Results</h2>" . $myAwards . $myTournamentResults;
		}

	}
	//Coach Reminders
	if(userHasPrivilege(5))
	{
		$output.="<p><div class='btn-group' role='group' aria-label='Coach Analysis Buttons'>";
		$output.="<a class='btn btn-info' role='button' href='#tournamentsscore' data-toggle='tooltip' data-placement='top' title='Overall Scores'><span class='bi bi-graph-up'></span> Student Ranking</a>";
		$output.=" <a class='btn btn-info' role='button' href='#tournamentsscoreevent' data-toggle='tooltip' data-placement='top' title='Event Scores'><span class='bi bi-hammer'></span> Event Scores</a>";
		$output.=" <a class='btn btn-info' role='button' href='#tournamentsscoreeventoverall'  data-toggle='tooltip' data-placement='top' title='Event Performance'><span class='bi bi-fuel-pump'></span> Event Performance</a>";
		$output.="</div></p>";
	}

}else{
	$output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
}
echo $output;
?>
