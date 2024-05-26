<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/remove.php"); //Check to make sure user is logged in and has privileges

userCheckPrivilege(5);
$schoolID = $_SESSION['userData']['schoolID'];
$tournamentID = intval($_POST['myID']);
if($tournamentID)
{
	if(checkNotSchoolID($schoolID,"tournament","tournamentID",$tournamentID)){
		exit ("You do not have permission to remove this tournament.");
	}
	//Checks to make sure deleting the tournament is not going to destroy other parts of the database.
	/*if(	checkStudentinTable("teammate",$studentID)){
	makeStudentInactive($studentID);
	exit ("Student has been placed on a team for a tournament and therefore, cannot be deleted. Instead, student made inactive.");
}
if(	checkStudentinTable("teammateplace",$studentID)){
makeStudentInactive($studentID);
exit ("Student has competed in a tournament and therefore, cannot be deleted. Instead, student made inactive.");
}
if(	checkStudentinTable("eventyear",$studentID)){
makeStudentInactive($studentID);
exit ("Student is an event leader and therefore, cannot be deleted. Instead, student made inactive.");
}
if(	checkStudentinTable("officer",$studentID)){
makeStudentInactive($studentID);
exit ("Student is an officer and therefore, cannot be deleted. Instead, student made inactive.");
}*/



//TODO: NOT COMPLETE

//Remove student from all tables
deletefromTable("team","tournamentID",$tournamentID);
deletefromTable("tournament","tournamentID",$tournamentID);

exit ("1");
}
exit ("Tournament ID not sent.");

?>
