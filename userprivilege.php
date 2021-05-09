<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

//Check permissions to make these SDO_DAS_ChangeSummary
if($_SESSION['userData']['privilege']<4)
{
	echo "The current user does not have privilege for this change.";
	exit;
}

//text output
$userID = intval($_REQUEST['userID']);
$privilege = intval($_REQUEST['privilege']);

//check to see that the user is not trying to up their own privilege or a friend's privilege to a higher level
if($privilege>$_SESSION['userData']['privilege']<3)
{
	echo "This user cannot set privileges higher than their own privilege.";
	exit;
}

//check to see that user exists
$query = "SELECT `id` FROM `user` WHERE `users`.`id` = $userID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$row = $result->fetch_assoc();
if (empty($row))
{
	echo "User does not exist to change privileges.";
	exit;
}

//Make changes
$query = "UPDATE `user` SET `privilege`='$privilege' WHERE `users`.`id` = $userID";
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
