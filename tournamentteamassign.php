<?php
require_once  ("php/functions.php");
userCheckPrivilege(1);
$schoolID =$_SESSION['userData']['schoolID'] ;
$output = "";
$teamID = intval($_POST['myID']);
if(empty($teamID))
{
	echo "<div style='color:red'>teamID is not set.</div>";
	exit();
}
$mobile = isset($_POST['mobile'])?intval($_POST['mobile']):0;

//Get timeBlock Tournament Schedule
function timeBlockTournamentSchedule($db, $tournamentID, $timeBlockID, $teamID)
{
	$schedule="";
	$query = "SELECT DISTINCT `timeblock`.`timeblockID`,`event`.`eventID`, `tournamentevent`.`tournamenteventID`, `event`.`eventID`, `event`.`event`,`tournamentevent`.`note`,`timeblock`.`timeStart`,`timeblock`.`timeEnd` FROM `tournamenttimechosen`
	INNER JOIN `tournamentevent` on `tournamenttimechosen`.`tournamenteventID` = `tournamentevent`.`tournamenteventID`
	INNER JOIN `timeblock` on `tournamenttimechosen`.`timeblockID` = `timeblock`.`timeblockID`
	INNER JOIN  `event` on `tournamentevent`.`eventID` = `event`.`eventID`
	where `tournamenttimechosen`.`timeblockID` = $timeBlockID AND `tournamenttimechosen`.`teamID`=$teamID
	order by `event`.`event`";
	$result = $db->query($query) or print("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && $result->num_rows > 0){
		$firstRow = 1;
		while ($row = $result->fetch_assoc()):
			if($firstRow)
			{
				$time = date("g:iA",strtotime($row["timeStart"]))." - ".date("g:iA",strtotime($row["timeEnd"])) . ", " . date("F j, Y",strtotime($row["timeStart"])) ;
				$schedule.="<h4>$time</h4>";
				$schedule.="<table class='table table-hover table-striped'><thead class='table-dark'><tr><th>Event</th><th>Partners</th></tr></thead><tbody>";
				$firstRow = 0;
			}
			$schedule.="<tr>";
			$schedule.="<td><div><strong>".$row['event']."</strong></div><div>".$row['note']."</div></td>";
			$schedule.="<td>".partnersWithEmails($db,$row['tournamenteventID'], $teamID)."</td>";
			$schedule.="</tr>";
		endwhile;
		$schedule.="</tbody></table>";

	}
	return $schedule;
}

//Find the list of Events for the a team
function eventTournamentSchedule($db, $schoolID, $teamID, $year, $tournamenteventID, $eventID)
{
	$query = "SELECT `student`.`studentID`, `student`.`first`, `student`.`last`,`student`.`email`, `student`.`emailSchool`, `student`.`yearGraduating` FROM `team`
	INNER JOIN `teammateplace` ON `team`.`teamID`=`teammateplace`.`teamID`
	INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID`
	INNER JOIN `student` ON `teammateplace`.`studentID`=`student`.`studentID`
	WHERE `team`.`teamID` = $teamID AND `teammateplace`.`tournamenteventID`=$tournamenteventID
	ORDER BY `student`.`last`, `student`.`first`";
		$output="";
		$result = $db->query($query) or print("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$emails[] = NULL;
		$schoolEmails[]=NULL;
		$rows = 0;
		$leaderIDs = getEventLeaderIDs($db, $eventID, $year, $schoolID);
		if($result)
		{
			$rows = $result->num_rows;
			if ($rows > 0)
			{
				$firstRow = 1;
				while ($row = $result->fetch_assoc()):
					if($firstRow)
					{
						$output.="<table class='table table-hover table-striped'><thead class='table-dark'><tr><th>Name</th><th>Grade</th></tr></thead><tbody>";
						$firstRow = 0;
					}
					$output.="<tr>";
					$studentGrade=getStudentGrade($row['yearGraduating']);
					$eventLeader="";
					if(in_array($row['studentID'],$leaderIDs))
					{
						$eventLeader=" *Event Leader";
					}
					$output.="<td class='student' id='teammate-".$row['studentID']."'><a target='_blank' href='#student-details-".$row['studentID']."'>".
					$row['last'].", " . $row['first'] ."</a>$eventLeader</td>
					<td>$studentGrade</td>";

					$emails[] = $row['email'];
					$schoolEmails[] = $row['emailSchool'];
				endwhile;
				//displays total students
				//$output.="<tr><td>Total Students</td><td>$rows</td></tr>";
				//email list does not seem necessary for the team view
				/*if(userHasPrivilege(2))
				{
					$output.="<tr>";
					$emailList = implode('; ', $emails);
					$schoolEmailList= implode('; ', $schoolEmails);
					$output.="<td colspan='2'><a href='mailto: $emailList'>Personal Emails</a>, <a href='mailto: $schoolEmailList'>School Emails</a>, <a href='mailto: $emailList ; $schoolEmailList'>All Emails</a></td>";
					$output.="</tr>";
				}*/
				$output.="</tbody></table>";	
			}
		}
		return $output;
}

//Get team and tournament row information
$query = "SELECT * FROM `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID` WHERE `teamID` = $teamID AND `tournament`.`schoolID`=$schoolID";
$resultTeam = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$rowTeam = $resultTeam->fetch_assoc();
$maxPlace = $rowTeam['teamsAttended'];

if(!$mobile)
{

	//Get tournament times
	$query = "SELECT * FROM `timeblock` WHERE `tournamentID` = ".$rowTeam['tournamentID']." ORDER BY `timeStart`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output .="<h2>";
	if(mysqli_num_rows($result))
	{
		if(userHasPrivilege(3)){
			if($rowTeam['dateTournament']>date("Y-m-d")||$rowTeam['notCompetition']){
				$output .="Adjust Teammate Assignments";
			}
			else {
				$output .="Modify Results";
			}
		}
		else{
			if($rowTeam['dateTournament']<date("Y-m-d")){
				//Show results as title after tournament date
				$output .="Results";
			}
			else {
				//Show schedule as title before and during tournament date
				$output .="Schedule";
			}
		}
		$output .=" <span id='myTitle'>".$rowTeam['tournamentName'].": ".$rowTeam['teamName']."</span></h2><div id='note'></div>";

		$output .="<form id='changeme' method='post' action='tournamentChangeMe.php'><table id='tournamentTable' class='tournament table table-hover'>";
		$timeblocks = [];
		while ($row = $result->fetch_assoc()):
			$query = "SELECT * FROM `tournamenttimechosen` INNER JOIN `tournamentevent` ON `tournamenttimechosen`.`tournamenteventID`=`tournamentevent`.`tournamenteventID` INNER JOIN `event` ON `tournamentevent`.`eventID`=`event`.`eventID` WHERE `timeblockID` = ".$row['timeblockID']." AND `tournamenttimechosen`.`teamID`= $teamID ORDER BY `event`.`event`";
			$resultEvents = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
			$events = [];
			while ($rowEvent = $resultEvents->fetch_assoc()):
				$rowEvent['eventTotal']=0;
				array_push($events, $rowEvent);
			endwhile;
			$row['events'] = $events; //add count of events in timeblock
			array_push($timeblocks, $row);
		endwhile;

		//Run through times and figure out the number of different dates and print columns with colspan of times for that date
		$output .="<thead><tr><th rowspan='3' style='vertical-align:bottom;'><div>Students</div></th><th rowspan='4' style='vertical-align:bottom;'>Grade</th>";

		$dateCheck = "";
		$dateColSpan = 0;
		$dateCount = 0;
		foreach ($timeblocks as $timeblock) {
			$eventNumber = count($timeblock['events'])>0?count($timeblock['events']):1;
			if($dateCheck==""){
				$dateCheck=date("F j, Y",strtotime($timeblock["timeStart"]));
				$dateColSpan = $eventNumber;
			}
			else {
				if($dateCheck!=date("F j, Y",strtotime($timeblock["timeStart"]))){
					$output .= "<th colspan='$dateColSpan' style='border-right:2px solid black;text-align:center;'>" . $dateCheck . "</th>";
					$dateCheck=date("F j, Y",strtotime($timeblock["timeStart"]));
					$dateColSpan = $eventNumber;
					$dateCount +=1;
					$timeblock['border'] = "border-left:2px solid black; "; //adds border at beginning of new date
				}
				else {
					$dateColSpan += $eventNumber;
				}
			}
		}
		$output .= "<th colspan='$dateColSpan' style='text-align:center;'>" . $dateCheck . "</th>";
		$output .="<th rowspan='3' style='vertical-align:bottom;'>Total Events</th></tr>";

		//print the time for each event and date
		$output .="<tr>";
		for ($i = 0; $i < count($timeblocks); $i++) {
			$eventNumber = count($timeblocks[$i]['events'])>0?count($timeblocks[$i]['events']):1;
			$border = isset($timeblocks[$i]['border'])?$timeblocks[$i]['border']:"";
			$output .= "<th id='timeblock-".$timeblocks[$i]['timeblockID']."' colspan='$eventNumber' style='".$border."background-color:".rainbow($i)."'>" . timeblockEdit($timeblocks[$i]['timeblockID'],date("g:iA",strtotime($timeblocks[$i]["timeStart"])) ." - " . date("g:iA",strtotime($timeblocks[$i]["timeEnd"])),(userHasPrivilege(3)))  . "</th>";
		}
		$output .="</tr>";

		//print the event under each time
		$output .="<tr>";
		$totalEvents =0;
		foreach ($timeblocks as $i=>$timeblock) {
			$timeEvents= $timeblock['events'];
			if($timeEvents)
			{
				foreach ($timeEvents as $timeEvent) {
					$border = isset($timeblock['border'])?$timeblock['border']:"";
					$output .= "<th id='event-".$timeEvent['tournamenteventID']."' style='".$border."background-color:".rainbow($i)."'><span>".$timeEvent['event']."</span></th>";
					$totalEvents +=1;
				}
			}
			else {
				$border = isset($timeblock['border'])?$timeblock['border']:"";
				$output .= "<th style='$border background-color:".rainbow($i)."'></th>";
			}

		}
		$output .="</tr>";

		//print the event note under each event
		$output .="<tr>";
		//put sorting for last and first name in this row
		$output .="<th><a href='javascript:tournamentSort(`tournamentTable`,`studentLast`)'>Last</a>, <a href='javascript:tournamentSort(`tournamentTable`,`studentFirst`)'>First</a></th>";

		foreach ($timeblocks as $i=>$timeblock) {
			$timeEvents= $timeblock['events'];
			if($timeEvents)
			{
				foreach ($timeEvents as $timeEvent) {
					$border = isset($timeblock['border'])?$timeblock['border']:"";
					$output .= "<th id='event-".$timeEvent['tournamenteventID']."' style='".$border."background-color:".rainbow($i)."'>".eventNote($timeEvent['tournamenteventID'],$timeEvent['note'],(userHasPrivilege(3)))."</th>";
				}
			}
			else {
				$border = isset($timeblock['border'])?$timeblock['border']:"";
				$output .= "<th style='$border background-color:".rainbow($i)."'></th>";
			}

		}
		$output .="<th>$totalEvents</th></tr></thead><tbody>";

		//Get students
		$query = "SELECT * FROM `teammate` INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID` WHERE `teamID` = $teamID ORDER BY `last` ASC, `first` ASC";
		$resultStudent = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$totalStudents = mysqli_num_rows($resultStudent);
		$totalSeniors = 0;
		if($totalStudents)
		{
			while ($rowStudent = $resultStudent->fetch_assoc()):
				//$studentTotal = 0;  //this is done in the javascript TODO: remove this line
				$output .="<tr studentLast=".removeParenthesisText($rowStudent['last'])."  studentFirst=".removeParenthesisText($rowStudent['first']).">";

				//find student Grade
				$studentGrade = getStudentGrade($rowStudent['yearGraduating']);
				$totalSeniors += $studentGrade==12 ? 1:0;
				//output student column
				$output .="<td class='student' id='teammate-".$rowStudent['studentID']."'><a target='_blank' href='#student-details-".$rowStudent['studentID']."'>".$rowStudent['last'].", " . $rowStudent['first'] ."</a></td><td>$studentGrade</td>";
				foreach ($timeblocks as $i=>$timeblock) {
					$timeEvents= $timeblock['events'];
					if($timeEvents)
					{
						foreach ($timeEvents as $timeEvent) {
							$checkbox = "teammateplace-".$timeEvent['tournamenteventID']."-".$rowStudent['studentID']."-".$teamID;
							$checkboxEvent = "timeblock-".$timeblock['timeblockID']." teammateEvent-".$timeEvent['tournamenteventID']." teammateStudent-".$rowStudent['studentID'] ." event-".$timeEvent['eventID'];

							$query = "SELECT * FROM `teammateplace` WHERE `tournamenteventID` =  ".$timeEvent['tournamenteventID']." AND `studentID` = ".$rowStudent['studentID']." AND `teamID` = $teamID";
							$resultTeammateplace = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
							$border = isset($timeblock['border'])?$timeblock['border']:"";
							$output .="<td style='$border background-color:".rainbow($i)."' class='$checkboxEvent' data-timeblock='".$timeblock['timeblockID']."'>";
							$checked = mysqli_num_rows($resultTeammateplace)?" checked ":"";
							//$timeEvent['eventTotal'] +=$checked?1:0;  //done in javascript
							//$studentTotal +=$checked?1:0;  //done in javascript
							if(userHasPrivilege(3)){
								$output .= "<input type='checkbox' onchange='javascript:tournamentEventTeammate($(this))' id='$checkbox' name='$checkbox' value='' data-timeblock='".$timeblock['timeblockID']."' $checked>";
							}
							else {
								$output .=$checked?"<span class='bi bi-check'></span>":"";
							}
							$output .="</td>";
						}
					}
					else {
						$border = isset($timeblock['border'])?$timeblock['border']:"";
						$output .= "<td style='$border background-color:".rainbow($i)."'></td>";
					}

				}
				$output .="<td id='studenttotal-".$rowStudent['studentID']."'></td></tr>";
			endwhile;
		}
		else {
			exit("Make sure to add students to this team before this step!");
		}

		//print the total signed up for each event
		$errorSeniors = $totalSeniors > 7 ? "<span class='error'>Too many</span>":"";
		$output .="</tbody><tfoot><tr><td><strong>$totalStudents</strong> Total Teammates</td><td><strong>$totalSeniors</strong> Seniors $errorSeniors</td>";
		foreach ($timeblocks as $i=>$timeblock) {
			$timeEvents= $timeblock['events'];
			if($timeEvents)
			{
				foreach ($timeEvents as $timeEvent) {
					$output .= "<td class='eventtotal' data-eventmax='".$timeEvent['numberStudents']."' id='eventtotal-".$timeEvent['tournamenteventID']."' style='$border background-color:".rainbow($i)."'></td>"; //event total is entered here via javascript
				}
			}
			else {
				$border = isset($timeblock['border'])?$timeblock['border']:"";
				$output .= "<td style='$border background-color:".rainbow($i)."'></td>";
			}
		}
		$output .="</tr>";

		//if this is a competitive tournament, enter/show placements here.  If this "tournament" is just for diplaying a team assignment, hide this.
		if(!$rowTeam['notCompetition'] && $rowTeam["dateTournament"]<=getCurrentTimestamp($mysqlConn))
		{
			//print the place for each event
			$output .="<tr class='placementRow'><td colspan='2'>Place</td>";
			foreach ($timeblocks as $i=>$timeblock) {
				$timeEvents= $timeblock['events'];
				if($timeEvents)
				{
					foreach ($timeEvents as $timeEvent) {
						$placeName = "placement-".$timeEvent['tournamenteventID']."--".$teamID;//do not put studentID here the -- makes this null
						$query = "SELECT * FROM `teammateplace` WHERE `tournamenteventID` =  ".$timeEvent['tournamenteventID']." AND `teamID` = $teamID";
						$resultTeammateplace = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
						$rowPlace="";
						if(mysqli_num_rows($resultTeammateplace))
						{
							$rowPlace = $resultTeammateplace->fetch_assoc();
						}
						$border = isset($timeblock['border'])?$timeblock['border']:"";
						$output .= "<td style='$border background-color:".rainbow($i)."'>";
						$place = isset($rowPlace['place'])?$rowPlace['place']:"";
						if(userHasPrivilege(3)){
							$maxPlaceDQ= $maxPlace+2;
							$output .= "<div><input id='$placeName' name='$placeName' class='placement' type='number' min='1' max='$maxPlaceDQ' onchange='javascript:tournamentEventTeammate($(this))' value='$place'/></div>";
						}
						else {
							$output .= $place;
						}
						$output .= "</td>";
					}
				}
				else {
					$border = isset($timeblock['border'])?$timeblock['border']:"";
					$output .= "<td style='$border background-color:".rainbow($i)."'></td>";
				}
			}
			$output .="</tr>";

		}
		//end table
		$output .="</tfoot></table>";
		//allow editing of team placement
		if(!$rowTeam['notCompetition'] && $rowTeam["dateTournament"]<=getCurrentTimestamp($mysqlConn))
		{
		$place = $rowTeam["teamPlace"];
		$score = $rowTeam["teamScore"];
		$output .= "<p id='teamPlacement'><label for='teamPlace'>Place</label>";
		$output .= " <input id='teamPlace' name='teamPlace' type='number' min='0' max='$maxPlace' value='$place' onchange=\"fieldUpdate('". $teamID ."','team','teamPlace',$(this).val(),'teamPlace','teamPlace')\"/>";
		$output .= "</p>";
		$output .= "<p>Total Score: <span id='teamScore'>$score</span></p>";
		}
		//end form
		$output .="</form>";
	}
	else {
		exit("<div>Set available time blocks first!</div>");
	}
}
else {

	//mobile version
	$output .="<h3>".$rowTeam['tournamentName']." - Team " . $rowTeam['teamName'] ."</h3>";
	$output .="<div class='btn-group' role='group' aria-label='Basic radio toggle button group' onchange='javascript:touramentCarouselToggle()'>";
	//By Student
	$output .="<input type='radio' class='btn-check' name='btnradio' id='btnradiostudent' autocomplete='off' value='student' checked>";
	$output .="<label class='btn btn-outline-primary' for='btnradiostudent'>Student</label>";
	//By Time Block
	$output .="<input type='radio' class='btn-check' name='btnradio' id='btnradiotime' autocomplete='off' value='time'>";
	$output .="<label class='btn btn-outline-primary' for='btnradiotime'>Time Block</label>";
	//By Event
	$output .="<input type='radio' class='btn-check' name='btnradio' id='btnradioEvent' autocomplete='off' value='event'>";
	$output .="<label class='btn btn-outline-primary' for='btnradioEvent'>Event</label>";
	$output .="</div>";

	//Get student information row information
	$query = "SELECT `student`.`studentID`, `student`.`first`, `student`.`last` FROM `team`
	INNER JOIN `teammate` ON `teammate`.`teamID`=`team`.`teamID`
	INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID`
	INNER JOIN `student` ON `teammate`.`studentID`=`student`.`studentID`
	WHERE `team`.`teamID` = $teamID
	ORDER BY `last`, `first`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0){
		$output .="<div id='studentCarousel' class='carousel-frame' >";
		for ($n = 0; $n < mysqli_num_rows($result); $n++) {
			//$output .="<button type='button' data-bs-target='#studentCarousel' data-bs-slide-to='$n' $active aria-label='Slide ". ($n+1) . "'></button>";
			//$active  = "";
		}
		//	$output .="</div>";
		//$output .="<div class='carousel-inner' style='min-height:450px;'>";
		//$active = "active";
		while ($row = $result->fetch_assoc()):
			$output .="<div>";
			$heading = $row['last'] . ", " . $row['first'];
			//$output .= "<div>" . $heading . "</div>";
			$output .=studentTournamentSchedule($mysqlConn, $rowTeam['tournamentID'], $row['studentID'], $heading);
			$output .="</div>";
		endwhile;
		$output .="</div>";
	}


	//Get student information by time block
	$query = "SELECT `timeblockID`,`timeStart`,`timeEnd`  FROM `timeblock`
	INNER JOIN `tournament` ON `timeblock`.`tournamentID`=`tournament`.`tournamentID`
	WHERE `tournament`.`tournamentID` = '".$rowTeam['tournamentID']."'
	ORDER BY `timeblock`.`timeStart`";
	$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0){

		$output .="<div id='timeCarousel' class='carousel-frame'  style='display:none'>";
		while ($row = $result->fetch_assoc()):
			$output .="<div>";
			//$output .=$row['timeStart'];
			$output .=timeBlockTournamentSchedule($mysqlConn, $rowTeam['tournamentID'], $row['timeblockID'], $teamID);

			//$heading = $row['last'] . ", " . $row['first'];
			//$output .=studentTournamentSchedule($mysqlConn, $rowTeam['tournamentID'], $row['studentID'], $heading);
			$output .="</div>";
		endwhile;
		$output .="</div>";
	}

	//Get student information by event
	$query = "SELECT `event`.`event`,`event`.`eventID`,`tournamentevent`.`tournamenteventID`, `tournamentevent`.`note`, `timeblock`.`timeStart`, `timeblock`.`timeEnd` FROM `tournamentevent`
	INNER JOIN `team` ON `tournamentevent`.`tournamentID` = `team`.`tournamentID` 
	INNER JOIN `event` ON `tournamentevent`.`eventID` = `event`.`eventID`
	INNER JOIN `tournamenttimechosen` ON `team`.`teamID`=`tournamenttimechosen`.`teamID` AND `tournamentevent`.`tournamenteventID`=`tournamenttimechosen`.`tournamenteventID`
	INNER JOIN `timeblock` ON `tournamenttimechosen`.`timeblockID`=`timeblock`.`timeblockID`
	WHERE `team`.`teamID` = $teamID ORDER BY `event`.`event`";
	$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result && mysqli_num_rows($result)>0){
	
		$output .="<div id='eventCarousel' class='carousel-frame'  style='display:none'>";
		while ($row = $result->fetch_assoc()):
			$output .="<div>";
			$output .="<h3>".$row['event']."</h3>";
			$time = date("g:iA",strtotime($row["timeStart"]))." - ".date("g:iA",strtotime($row["timeEnd"])) . ", " . date("l, F j, Y",strtotime($row["timeStart"])) ;
			$output.="<div><small class='text-muted'>$time</small></div>";
			$output .="<div>".$row['note']."</div>";
			$output .=eventTournamentSchedule($mysqlConn, $schoolID, $teamID, $rowTeam['year'], $row['tournamenteventID'], $row['eventID']);
			$output .="</div>";
		endwhile;
		$output .="</div>";
		}
}
/*
<div id="studentCarousel" class="carousel carousel-dark slide" data-bs-ride="false">
<div class="carousel-indicators">
<button type="button" data-bs-target="#studentCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
<button type="button" data-bs-target="#studentCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
<button type="button" data-bs-target="#studentCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
</div>
<div class="carousel-inner" style='height:<?=$height-225?>px'>
<div class="carousel-item active">
<div>One</div>
</div>
<div class="carousel-item">
<div>Two</div>
</div>
<div class="carousel-item">
<div>Three</div>
</div>
</div>
<button class="carousel-control-prev" type="button" data-bs-target="#studentCarousel" data-bs-slide="prev">
<span class="carousel-control-prev-icon" aria-hidden="true"></span>
<span class="visually-hidden">Previous</span>
</button>
<button class="carousel-control-next" type="button" data-bs-target="#studentCarousel" data-bs-slide="next">
<span class="carousel-control-next-icon" aria-hidden="true"></span>
<span class="visually-hidden">Next</span>
</button>
</div>
*/
if(userHasPrivilege(3))
{
	if($rowTeam["dateTournament"]>getCurrentTimestamp($mysqlConn)){
		$output .="<div id='tournamentTeamCopy'>".getTeamList($mysqlConn, $schoolID, $rowTeam['tournamentID'], "Assign Events from a Previous Tournament").
			"<input class='btn btn-primary' role='button' type='button' onclick='javascript:teamCopyAssignments($teamID)' value='Copy Event Assignments' /><br><br></div>";
	}
}
echo $output;
?>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>
