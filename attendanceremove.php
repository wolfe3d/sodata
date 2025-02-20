<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/remove.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
$schoolID = $_SESSION['userData']['schoolID'];

$meetingID = intval($_POST['myID']);
if($meetingID)
{
	deletefromTable("meeting",'meetingID',$meetingID);
	deletefromTable("meetingattendance",'meetingID',$meetingID);
	exit ("1");
}
exit ("Meeting ID not sent.");
?>
