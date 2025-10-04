<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/remove.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/functions.php");
userCheckPrivilege(3);

$meetingID = intval($_POST['myID']);
if($meetingID)
{
	deletefromTable("meeting",'meetingID',$meetingID);
	deletefromTable("meetingattendance",'meetingID',$meetingID);
	exit ("1");
}
exit ("Meeting ID not sent.");
?>
