<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$table = $mysqlConn->real_escape_string($_POST['table']);
if(empty($table))
{
	exit("<div style='color:red'>Table is not set.</div>");
}
$teamID = intval($_POST['teamID']);
if(empty($teamID))
{
	exit("<div style='color:red'>teamID is not set.</div>");
}
$studentID = intval($_POST['studentID']);
if(empty($studentID))
{
	exit("<div style='color:red'>studentID is not set.</div>");
}
$checked = intval($_POST['checked']);
if($checked){
	$query = "INSERT INTO `$table` (`teamID`, `studentID`) VALUES ('$teamID', '$studentID');";
}
else {
	$query = "DELETE FROM `$table` WHERE teamID = '$teamID' AND studentID = '$studentID';";
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
