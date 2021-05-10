<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$eventyearID = intval($_POST['eventyearID']);
$studentID =  intval($_POST['studentID']);
if(empty($eventyearID))
{
	echo "Missing the  eventyearID.  Cannot add to database.";
	exit;
}

//Insert event
$query = "UPDATE `eventyear` SET `studentID` = $studentID WHERE `eventyearID`='$eventyearID'";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if ($result)
{
	echo "1";
}
else
{
	echo "Update of eventyear failed: ". $mysqlConn->error;
}
?>
