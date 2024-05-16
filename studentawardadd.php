<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(2);

//text output
$output = "";
$studentID = intval($_REQUEST['studentID']);
$awardDate = $mysqlConn->real_escape_string($_POST['awardDate']);
$awardName = $mysqlConn->real_escape_string($_POST['awardName']);
$note = $mysqlConn->real_escape_string($_POST['note']);
$tournamentID = intval($_POST['tournamentID']??null);

//Check to make sure award with the same name, date, and student is not added
$query = "SELECT `awardID` FROM `award` WHERE `studentID`='$studentID' AND `awardDate` = '$awardDate' AND `awardName`='$awardName'";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($result && mysqli_num_rows($result)>0)
{
	exit("<p>Award already exists for this date with the same name for this student.  The duplicate award has not been added.</p>");
}
else 
{
	$query = "INSERT INTO `award` (`studentID`, `awardName`, `awardDate`, `note`, `tournamentID`) VALUES ('$studentID', '$awardName', '$awardDate', '$note', '$tournamentID') ";
	if ($mysqlConn->query($query) === TRUE)
	{
		echo $mysqlConn->insert_id;
	}
	else
	{
		error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$error="\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".";

		exit($error);
	}
}
?>
