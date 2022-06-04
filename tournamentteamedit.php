<?php
require_once  ("php/functions.php");
userCheckPrivilege(2);

/*Warnings for senior count and team cound is all handled in javascript
	*count number of students
	*count number of seniors
	*Warn if under 15 students
	*Error if over 15 students OR over 7 seniors
*/

function assignedToTeam($db, $teamID, $studentID)
{
	//$query = "SELECT * FROM `teammate` INNER JOIN `team` ON `teammate`.`teamID`=`team`.`teamID` WHERE `teammate`.`teamID` = $teamID";
	$query = "SELECT * FROM `teammate` WHERE `teamID` =  $teamID AND `studentID` = $studentID" ;
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	return mysqli_num_rows($result);
}
function assignedToOtherTeam($db, $tournamentID, $studentID)
{
	//$query = "SELECT * FROM `teammate` INNER JOIN `team` ON `teammate`.`teamID`=`team`.`teamID` WHERE `teammate`.`teamID` = $teamID";
	$query = "SELECT * FROM `teammate` INNER JOIN `team` ON `teammate`.`teamID`=`team`.`teamID` WHERE `team`.`tournamentID` =  $tournamentID AND `studentID` = $studentID" ;
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$row = $result->fetch_assoc();
	if(empty($row))
	{
		return "";
	}
	return "(Assigned to team " . $row['teamName'] . ")";
}

$teamID= intval($_POST['myID']);
if(empty($teamID))
{
	exit("<div style='color:red'>TeamID is not set.</div>");
}
//TODO: Hide getCurrentTimeStamp variable, then use a js script to recheck every x time.
echo getCurrentTimestamp($mysqlConn);

$query = "SELECT * FROM `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID` WHERE `teamID` = $teamID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$row = $result->fetch_assoc();
if(empty($row))
{
	exit("<div style='color:red'>No team found with ID: $teamID.</div>");
}
$teamName = $row['teamName'];
$tournamentID = $row['tournamentID'];
$studentList="<div class='scioly'><input type='checkbox' id='toggleInactive' name='toggleInactive' onchange='$(\".inactive\").toggle()' /><label for='toggleInactive'>Show Inactive Students</label></div><br>";
$query = "SELECT * FROM `student` ORDER BY `last` ASC, `first` ASC";
$resultStudent = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if($resultStudent){
	while ($rowStudent = $resultStudent->fetch_assoc()):
		$checkbox = "teammate-".$teamID."-".$rowStudent['studentID'];

		//$query = "SELECT * FROM `teammate` WHERE `teamID` =  $teamID AND `studentID` = ".$rowStudent['studentID'] ;
		//$resultTeammate= $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		//if there is a result then make box checked, if not do not check box.
		$checked = assignedToTeam($mysqlConn, $teamID, $rowStudent['studentID'])?" checked ":"";
		$assigned = "";
		$disabled = "";
		if (!$checked)
		{
			$assigned = assignedToOtherTeam($mysqlConn, $tournamentID, $rowStudent['studentID']);
			if($assigned)
			{
				$disabled = " disabled='disabled' ";
			}
		}
		$hidden = $rowStudent['active']?"":"class='inactive' style='display: none;'";
		$studentList .= "<div $hidden class='scioly'><input type='checkbox' data-studentgrade='".getStudentGrade($rowStudent['yearGraduating'])."' onchange='javascript:tournamentTeammate($(this))' id='$checkbox' name='$checkbox' value='' $checked $disabled><label for='$checkbox'><a target='_blank' href='#student-details-".$rowStudent['studentID']."'>".$rowStudent['last'].", " . $rowStudent['first'] ." - " . getStudentGrade($rowStudent['yearGraduating']) ."</a> $assigned</label></div>";
	endwhile;
}
?>
<div id='myTitle'><?=$row['tournamentName']?> - <?=$row['year']?></div>
<div id="note"></div>
<form id="tournamentTeamForm" method="post" action="fieldUpdate.php">
	<p id="teamNamep">
		<label for="teamName">Team Name</label>
		<input id="teamName" name="teamName" type="text" value="<?=$teamName?$teamName:'A'?>" onchange="fieldUpdate('<?=$teamID?>','team','teamName',$(this).val(),'teamName','teamName')">
	</p>
	<!---
	//TODO: ADD Javascript and php backend for the function below
	<p id="tournamentTeamp">
		<label for="tournamentTeam">Select Students from a Previous Tournament (TODO: Function not added yet)</label>
		<?=getTeamsPrevious($mysqlConn, $row['tournamentID'])?>
		<br>
		<input class="button" type="button" onclick="" value="Select" />
	</p>
-->
	<p>
		<?=$studentList?>
	</p>
	<hr>
	<div># of Seniors = <span id="seniors"></span></div>
	<div>Total Students = <span id="students"></span></div>
	<br>
	<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='fa fa-arrow-circle-left'></span> Return</button></p>
</form>
