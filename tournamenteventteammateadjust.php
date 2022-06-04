<?php
require_once  ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$tournamenteventID = $mysqlConn->real_escape_string($_POST['tournamenteventID']);
if(empty($tournamenteventID))
{
	exit("<div style='color:red'>tournamenteventID is not set.</div>");
}
$teamID = intval($_POST['teamID']);
if(empty($teamID))
{
	exit("<div style='color:red'>teamID is not set.</div>");
}
$studentID = intval($_POST['studentID']);
if(empty($studentID))
{
	//studentID is null if the place is changed for all students
}
$place = intval($_POST['place']);
if(empty($place))
{
	$place = 'NULL';
}
$checked = intval($_POST['checked']);
if($checked){
	$query = "INSERT INTO `teammateplace` (`tournamenteventID`, `teamID`, `studentID`, `place`) VALUES ('$tournamenteventID', '$teamID', '$studentID', $place);";
}
else {
	if(empty($studentID)){
		//if $studentID is not set, then this is being called to add place
		$query = "UPDATE `teammateplace` SET `place` = '$place' WHERE `tournamenteventID` = '$tournamenteventID' AND `teamID` = '$teamID';";
	}
	else {
		$query = "DELETE FROM `teammateplace` WHERE `tournamenteventID` = '$tournamenteventID' AND `teamID` = '$teamID' AND `studentID` = '$studentID';";
	}
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
