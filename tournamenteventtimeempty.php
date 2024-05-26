<?php
require_once  ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/functionstournament.php");
userCheckPrivilege(3);

$tournamenteventID = intval($_POST['tournamentevent']);
if(empty($tournamenteventID))
{
	exit("<div style='color:red'>tournamenteventID is not set.</div>");
}

if(!tournamentTimeChosenAllEmpty($tournamenteventID))
{
	exit("There is a timeblock chosen for a team for this event.  You must unselect the time before removing the event.");
}
echo "1";
?>
