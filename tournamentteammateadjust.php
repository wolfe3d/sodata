<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$table = $mysqlConn->real_escape_string($_POST['table']);
if(empty($table))
{
	exit("<div class='error'>Table is not set.</div>");
}
$teamID = intval($_POST['teamID']);
if(empty($teamID))
{
	exit("<div class='error'>teamID is not set.</div>");
}
$studentID = intval($_POST['studentID']);
if(empty($studentID))
{
	exit("<div class='error'>studentID is not set.</div>");
}
$checked = intval($_POST['checked']);
if($checked){
	//check to see if the person is assigned on another team
	$query = "SELECT * FROM `teammateplace` inner join `tournamentevent` on `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` where `tournamentID` = (SELECT `tournamentID` from `team` where `team`.teamID = $teamID) and `teamID` != $teamID and `studentID` = $studentID";
	//Insert student into team table
	$eventResult = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(!($eventResult && mysqli_num_rows($eventResult)>0)){
		$query = "INSERT INTO `$table` (`teamID`, `studentID`) VALUES ('$teamID', '$studentID');";
	}else{
		exit("2");  //student is assigned to another team
	}
}
else {
	//check to see if the student is assigned events on this teams
//TODO: Check to make sure student is not already assigned events


	//check to see if the student is on this team
	$query = "SELECT * FROM `teammateplace` where teamID = $teamID and studentID = $studentID";
	//Insert student into team table
	$eventResult = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(!($eventResult && mysqli_num_rows($eventResult)>0)){
		$query = "DELETE FROM `$table` WHERE `teamID` = '$teamID' AND `studentID` = '$studentID';";
	}else{
		exit("0");  //student is not assigned to this team
	}
}
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($result)
{
	exit("1");
}
else
{
	exit( $mysqlConn->error);
}

?>
