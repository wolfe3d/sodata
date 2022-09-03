<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/remove.php"); //Check to make sure user is logged in and has privileges

userCheckPrivilege(5);
$schoolID = $_SESSION['userData']['schoolID'];
$tournamentID = intval($_POST['myID']);
if($tournamentID)
{
	if(checkNotSchoolID($mysqlConn,$schoolID,"tournament","tournamentID",$tournamentID)){
		exit ("You do not have permission to remove this tournament.");
	}
	//Checks to make sure deleting the tournament is not going to destroy other parts of the database.
	/*if(	checkStudentinTable($mysqlConn,"teammate",$studentID)){
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
}*/



//TODO: NOT COMPLETE

//Remove student from all tables
deletefromTable($mysqlConn,"team","tournamentID",$tournamentID);
deletefromTable($mysqlConn,"tournament","tournamentID",$tournamentID);

exit ("1");
}
exit ("Tournament ID not sent.");

?>
