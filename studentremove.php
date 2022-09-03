<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/remove.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(5);
$schoolID = $_SESSION['userData']['schoolID'];

$studentID = intval($_POST['myID']);
if($studentID)
{
	//Check to make sure you have permission to remove this student
	if(checkNotSchoolID($mysqlConn,$schoolID,"student","studentID",$studentID)){
		exit ("You do not have permission to remove this tournament.");
	}
	//Checks to make sure deleting the student is not going to destroy other parts of the database.
	if(checkinTable($mysqlConn,"teammate",'studentID',$studentID)){
		makeStudentInactive($mysqlConn, $studentID);
		exit ("Student has been placed on a team for a tournament and therefore, cannot be deleted. Instead, student made inactive.");
	}
	if(checkinTable($mysqlConn,"teammateplace",'studentID',$studentID)){
		makeStudentInactive($mysqlConn, $studentID);
		exit ("Student has competed in a tournament and therefore, cannot be deleted. Instead, student made inactive.");
	}
	if(checkinTable($mysqlConn,"eventyear",'studentID',$studentID)){
		makeStudentInactive($mysqlConn, $studentID);
		exit ("Student is an event leader and therefore, cannot be deleted. Instead, student made inactive.");
	}
	if(checkinTable($mysqlConn,"officer",'studentID',$studentID)){
		makeStudentInactive($mysqlConn, $studentID);
		exit ("Student is an officer and therefore, cannot be deleted. Instead, student made inactive.");
	}

	//Remove student from all tables
	deletefromTable($mysqlConn,"award",'studentID',$studentID);
	deletefromTable($mysqlConn,"coursecompleted",'studentID',$studentID);
	deletefromTable($mysqlConn,"courseenrolled",'studentID',$studentID);
	deletefromTable($mysqlConn,"eventchoice",'studentID',$studentID);
	deletefromTable($mysqlConn,"officer",'studentID',$studentID);
	deletefromTable($mysqlConn,"studentPlacement",'studentID',$studentID);
	deletefromTable($mysqlConn,"student",'studentID',$studentID);

	exit ("1");
}
exit ("Student ID not sent.");

function makeStudentInactive($db, $studentID)
{
	$query = "UPDATE `student` SET `active` = '0' WHERE `student`.`studentID` = $studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
}
?>
