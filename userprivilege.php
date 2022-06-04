<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3); //minimum privilege to edit privileges

//text output
$userID = intval($_REQUEST['userID']);
if(empty($userID))
{
	exit("<div style='color:red'>userID is not set.</div>");
}

$privilege = intval($_REQUEST['privilege']);
if(empty($privilege))
{
	exit("<div style='color:red'>privilege is not set.</div>");
}

//check to see that the user is not trying to up their own privilege or a friend's privilege to a higher level
if(!userHasPrivilege($privilege))
{
	exit("This user cannot set privileges higher than their own privilege.");
}

//check to see that user exists
$query = "SELECT `userID` FROM `user` WHERE `user`.`userID` = $userID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if(!$result)
{
	exit("Query error:".$query);
}
$row = $result->fetch_assoc();
if (empty($row))
{
	echo "User does not exist to change privileges.";
	exit;
}

//Make changes
$query = "UPDATE `user` SET `privilege`='$privilege' WHERE `user`.`userID` = $userID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if ($result === TRUE)
{
	echo "*Record Edited";
}
else
{
	echo json_encode(array("error"=>"Error_addStudent: $queryInsert $mysqlConn->error"));
}
?>
