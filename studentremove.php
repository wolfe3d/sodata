<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$studentID = intval($_POST['myID']);
if($studentID)
{
	//Checks to make sure deleting the student is not going to destroy other parts of the database.
	if(	checkStudentinTable($mysqlConn,"teammate",$studentID)){
		makeStudentInactive($mysqlConn, $studentID);
		exit ("Student has been placed on a team for a tournament and therefore, cannot be deleted. Instead, student made inactive.");
	}
	if(	checkStudentinTable($mysqlConn,"teammateplace",$studentID)){
		makeStudentInactive($mysqlConn, $studentID);
		exit ("Student has competed in a tournament and therefore, cannot be deleted. Instead, student made inactive.");
	}
	if(	checkStudentinTable($mysqlConn,"eventyear",$studentID)){
		makeStudentInactive($mysqlConn, $studentID);
		exit ("Student is an event leader and therefore, cannot be deleted. Instead, student made inactive.");
	}
	if(	checkStudentinTable($mysqlConn,"officer",$studentID)){
		makeStudentInactive($mysqlConn, $studentID);
		exit ("Student is an officer and therefore, cannot be deleted. Instead, student made inactive.");
	}

	//Remove student from all tables
	deleteStudentfromTable($mysqlConn,"award",$studentID);
	deleteStudentfromTable($mysqlConn,"coursecompleted",$studentID);
	deleteStudentfromTable($mysqlConn,"courseenrolled",$studentID);
	deleteStudentfromTable($mysqlConn,"eventchoice",$studentID);
	deleteStudentfromTable($mysqlConn,"officer",$studentID);
	deleteStudentfromTable($mysqlConn,"studentPlacement",$studentID);
	deleteStudentfromTable($mysqlConn,"student",$studentID);

	exit ("1");
}
exit ("Student ID not sent.");

function deleteStudentfromTable($db, $tableName,$studentID)
{
	$query = "DELETE FROM `$tableName` WHERE `$tableName`.`studentID` = $studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
}
function checkStudentinTable($db, $tableName,$studentID)
{
	$query = "SELECT * FROM `$tableName` WHERE `$tableName`.`studentID` = $studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		return 1;
	}
	return 0;
}
function makeStudentInactive($db, $studentID)
{
	$query = "UPDATE `student` SET `active` = '0' WHERE `student`.`studentID` = $studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
}
?>
