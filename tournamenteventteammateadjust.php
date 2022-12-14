<?php
require_once  ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/functions.php");
userCheckPrivilege(3);

$tournamenteventID = $mysqlConn->real_escape_string($_POST['tournamenteventID']);
if(empty($tournamenteventID))
{
	exit("<div style='color:red'>tournamenteventID is not set.</div>");
}
$teamID = intval($_POST['teamID']);
if(empty($teamID))
{
	exit("<div style='color:red'>teamID is not set.</div>");
}
$studentID = intval($_POST['studentID']);
if(empty($studentID))
{
	//studentID is null if the place is changed for all students
}

$place = getIfSet($_POST['place'],0);
$checked = getIfSet($_POST['checked'],0);
if($checked){
	//check to make sure student is on the team
	$query = "SELECT `teammateID` FROM `teammate` WHERE `teammate`.`teamID`=$teamID AND `teammate`.`studentID`=$studentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(!($result && mysqli_num_rows($result)>0))
	{
		exit ('2'); //If student is not on the team, then the student cannot be added to an event.  This would only appear if someone removes a student, while another user / browser has the event open.
	}

 //Check to see if student is already assigned by another user / browser
 $query = "SELECT `teammateplaceID` FROM `teammateplace` WHERE `teamID`=$teamID AND `studentID`=$studentID AND `tournamenteventID`=$tournamenteventID";
 $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
 if($result && mysqli_num_rows($result)>0)
 {
 	exit ('3'); //Event is already added by another user.  This would only appear if someone added an event while this user attempts to do the same thing.
 }
 else {
	 $query = "INSERT INTO `teammateplace` (`tournamenteventID`, `teamID`, `studentID`) VALUES ('$tournamenteventID', '$teamID', '$studentID');";
 }
}
else {
	if(empty($studentID)){
		//if $studentID is not set, then this is being called to add place
		$query = "UPDATE `teammateplace` SET `place` = '$place' WHERE `tournamenteventID` = '$tournamenteventID' AND `teamID` = '$teamID';";
	}
	else {
		$query = "DELETE FROM `teammateplace` WHERE `tournamenteventID` = '$tournamenteventID' AND `teamID` = '$teamID' AND `studentID` = '$studentID';";
	}
}
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($result)
{
	exit('1');
}
else
{
	echo $mysqlConn->error;
}

?>
