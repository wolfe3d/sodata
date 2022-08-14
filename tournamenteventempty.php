<?php
//this is a function to check if the tournament's event is empty (no teammates) before deletion

require_once  ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$tournamenteventID = intval($_POST['tournamentevent']);
if(empty($tournamenteventID)){
	exit("<div style='color:red'>tournamenteventID is not set.</div>");
}

//check to make sure student is not assigned to this event at this tournament
$query = "SELECT `studentID` FROM `teammateplace` WHERE `tournamenteventID`=$tournamenteventID;";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($result&&mysqli_num_rows($result))
{
	exit("This event cannot be removed.  A teammate is assigned to the event.");
}
exit ("1");
?>
