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
	if(checkNotSchoolID($schoolID,"student","studentID",$studentID)){
		exit ("You do not have permission to remove this tournament.");
	}
	//Checks to make sure deleting the student is not going to destroy other parts of the database.
	if(checkinTable("teammate",'studentID',$studentID)){
		makeStudentInactive($studentID);
		exit ("Student has been placed on a team for a tournament and therefore, cannot be deleted. Instead, student made inactive.");
	}
	if(checkinTable("teammateplace",'studentID',$studentID)){
		makeStudentInactive($studentID);
		exit ("Student has competed in a tournament and therefore, cannot be deleted. Instead, student made inactive.");
	}
	if(checkinTable("eventyear",'studentID',$studentID)){
		makeStudentInactive($studentID);
		exit ("Student is an event leader and therefore, cannot be deleted. Instead, student made inactive.");
	}
	if(checkinTable("officer",'studentID',$studentID)){
		makeStudentInactive($studentID);
		exit ("Student is an officer and therefore, cannot be deleted. Instead, student made inactive.");
	}

	//Remove student from all tables
	deletefromTable("award",'studentID',$studentID);
	deletefromTable("coursecompleted",'studentID',$studentID);
	deletefromTable("courseenrolled",'studentID',$studentID);
	deletefromTable("eventchoice",'studentID',$studentID);
	deletefromTable("officer",'studentID',$studentID);
	deletefromTable("studentPlacement",'studentID',$studentID);
	deletefromTable("student",'studentID',$studentID);

	exit ("1");
}
exit ("Student ID not sent.");

function makeStudentInactive($studentID)
{
	global $mysqlConn;
	$query = "UPDATE `student` SET `active` = '0' WHERE `student`.`studentID` = $studentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
}
?>
