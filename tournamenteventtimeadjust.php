<?php
require_once  ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

//javascript prevents assigning the same event to two different event blocks for in table tournamenttimechosen
//TODO: Also, block one event from being assigned two different time blocks in table tournamenttimechosen
//It is acceptable to have many time blocks chosen for one event in tournamenttimeavailable
$table = $mysqlConn->real_escape_string($_POST['table']);
if(empty($table))
{
	exit("<div style='color:red'>Table is not set.</div>");
}
$tournamenteventID = intval($_POST['tournamenteventID']);
if(empty($tournamenteventID))
{
	exit("<div style='color:red'>tournamenteventID is not set.</div>");
}
$timeblockID = intval($_POST['timeblockID']);
if(empty($timeblockID))
{
	exit("<div style='color:red'>timeblockID is not set.</div>");
}
$checked = intval($_POST['checked']);
if($checked)
{
	if($table=="tournamenttimeavailable")
	{
		$query = "INSERT INTO `$table` (`tournamenteventID`, `timeblockID`) VALUES ('$tournamenteventID', '$timeblockID');";
	}
	else if($table=="tournamenttimechosen")
	{
		$teamID = intval($_POST['teamID']);
		if(empty($teamID))
		{
			exit("<div style='color:red'>Team is not set.</div>");
		}
		$query = "INSERT INTO `$table` (`tournamenteventID`, `timeblockID`,`teamID`) VALUES ('$tournamenteventID', '$timeblockID','$teamID');";
	}
	else{
		exit("<div style='color:red'>Table is not correctly set.</div>");
	}
}
else {
	//TODO: Instead of removing a tournamenteventID in tournamenteventchosen that may be linked to students already.  Try modifying the time block.
  //TODO: Answer question -- what if the tournamenttimeavailable is wrong and the tournamenttimechosen used one of these available times.  IF you remove the time from tournamenttimeavailable, how does that affect the db?
	$query = "DELETE FROM `$table` WHERE `tournamenteventID` = '$tournamenteventID' AND `timeblockID` = '$timeblockID';";
}
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($result)
{
	echo "1";
}
else
{
	echo $mysqlConn->error;
}

?>
