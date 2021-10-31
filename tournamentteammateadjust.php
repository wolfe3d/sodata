<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
$jsOutput = "";

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
	$query = "SELECT * FROM `teammateplace` inner join `tournamentevent` on `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` where `tournamentID` = (SELECT `tournamentID` from `team` where `team`.teamID = $teamID) and `teamID` != $teamID and `studentID` = $studentID";
	//Insert student into team table
	$eventResult = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(!($eventResult && mysqli_num_rows($eventResult)>0)){
		$query = "INSERT INTO `$table` (`teamID`, `studentID`) VALUES ('$teamID', '$studentID');";
	}else{
		$jsOutput.="1";
	}


}
else {
	$query = "SELECT * FROM `teammateplace` where teamID = $teamID and studentID = $studentID";
	//Insert student into team table
	$eventResult = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(!($eventResult && mysqli_num_rows($eventResult)>0)){
		$query = "DELETE FROM `$table` WHERE `teamID` = '$teamID' AND `studentID` = '$studentID';";
	}else{
		$jsOutput.="0";
	}
}
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($result)
{
	$jsOutput.="1";
	echo $jsOutput;
}
else
{
	echo $mysqlConn->error;
}

?>
