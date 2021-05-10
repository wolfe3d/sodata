<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$eventyearID = intval($_REQUEST['eventyearID']);
if(empty($eventyearID))
{
	echo "Missing the eventyearID.  Cannot remove from database.";
	exit;
}

$query = "DELETE FROM `eventyear` WHERE `eventyear`.`eventyearID` = $eventyearID";
if ($mysqlConn->query($query) === TRUE)
{
	echo "1";
}
else
{
	error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	echo $mysqlConn->error;
}
?>
