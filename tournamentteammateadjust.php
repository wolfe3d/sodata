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
	//TODO: Make sure student is not assigned to a tournament in which they are already assigned to a different team
	//If student is assigned to another team, check box should be unchecked in data.js tournamentTeammate function and error should be given


	//Insert student into team table
	$query = "INSERT INTO `$table` (`teamID`, `studentID`) VALUES ('$teamID', '$studentID');";
}
else {
	//TODO: Make sure student is not already assigned events.  If they are give warning, do not remove student.
	//If student is assigned, check box should be rechecked in data.js tournamentTeammate function and error should be given
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
