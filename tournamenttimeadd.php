<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$tournamentID = intval($_POST['myID']);
//$blockColor = substr($mysqlConn->real_escape_string($_POST['blockColor']),1);
$timeStart = $mysqlConn->real_escape_string($_POST['timeStart']);
$timeEnd = $mysqlConn->real_escape_string($_POST['timeEnd']);
if(empty($tournamentID))
{
	echo "<div style='color:red'>tournamentID is not set.</div>";
	exit();
}

if(empty($timeStart)||empty($timeEnd)){
	echo "<div style='color:red'>No start or end time was set.</div>";
	exit();
}

$datetimeStart= date('Y-m-d H:i:s',strtotime($timeStart));
$datetimeEnd= date('Y-m-d H:i:s',strtotime($timeEnd));
$query = "INSERT INTO `timeblock` (`timeStart`, `timeEnd`, `tournamentID`) VALUES ('$datetimeStart', '$datetimeEnd', '$tournamentID');";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if ($result)
{
	echo $mysqlConn->insert_id;
}
else
{
	echo $mysqlConn->error;
}

?>
