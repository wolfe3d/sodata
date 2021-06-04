<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

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
	$query = "DELETE FROM `$table` WHERE tournamenteventID = '$tournamenteventID' AND timeblockID = '$timeblockID';";
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
