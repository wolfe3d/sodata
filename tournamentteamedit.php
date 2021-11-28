<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(2);
require_once  ("functions.php");
/*Warnings for senior count and team cound is all handled in javascript
	*count number of students
	*count number of seniors
	*Warn if under 15 students
	*Error if over 15 students OR over 7 seniors
*/
$teamID= intval($_POST['myID']);
if(empty($teamID))
{
	exit("<div style='color:red'>TeamID is not set.</div>");
}

$query = "SELECT * FROM `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID` WHERE `teamID` = $teamID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$row = $result->fetch_assoc();
if(empty($row))
{
	exit("<div style='color:red'>No team found with ID: $teamID.</div>");
}
$teamName = $row['teamName'];

$studentList="<div><input type='checkbox' id='toggleInactive' name='toggleInactive' onchange='$(\".inactive\").toggle()' /><label for='toggleInactive'>Show Inactive Students</label></div>";
$query = "SELECT * FROM `student` ORDER BY `last` ASC, `first` ASC";
$resultStudent = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if($resultStudent){
	while ($rowStudent = $resultStudent->fetch_assoc()):
		$checkbox = "teammate-".$teamID."-".$rowStudent['studentID'];
		$query = "SELECT * FROM `teammate` WHERE `teamID` =  $teamID AND `studentID` = ".$rowStudent['studentID'] ;
		$resultTeammate= $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		//if there is a result then make box checked, if not do not check box.
		$checked = mysqli_num_rows($resultTeammate)?" checked ":"";
		$hidden = $rowStudent['active']?"":"class='inactive' style='display: none;'";
		$studentList .= "<div $hidden><input type='checkbox' data-studentgrade='".getStudentGrade($rowStudent['yearGraduating'])."' onchange='javascript:tournamentTeammate($(this))' id='$checkbox' name='$checkbox' value='' $checked><label for='$checkbox'><a target='_blank' href='#student-details-".$rowStudent['studentID']."'>".$rowStudent['last'].", " . $rowStudent['first'] ." - " . getStudentGrade($rowStudent['yearGraduating']) ."</a></label></div>";
	endwhile;
}
?>
<div id='myTitle'><?=$row['tournamentName']?> - <?=$row['year']?></div>
<div id="note"></div>
<form id="tournamentTeamForm" method="post" action="fieldUpdate.php">
	<p id="teamName">
		<label for="teamName">Team Name</label>
		<input id="teamName" name="teamName" type="text" value="<?=$teamName?$teamName:'A'?>" onchange="fieldUpdate('<?=$teamID?>','team','teamName',$(this).val())">
	</p>
	<p id="tournamentTeam">
		<label for="tournamentTeam">Select Students from a Previous Tournament (TODO: Function not added yet)</label>
		<?=getTeamsPrevious($mysqlConn)?>
	</p>
	<p>
		<?=$studentList?>
	</p>
	<hr>
	<div># of Seniors = <span id="seniors"></span></div>
	<div>Total Students = <span id="students"></span></div>
	<br>
	<p>
		<input class="button" type="button" onclick="window.history.back()" value="Return" />
	</p>
</form>
