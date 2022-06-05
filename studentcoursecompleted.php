<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(2);
//text output
$output = "";

$myID = intval($_REQUEST['myID']);
if(empty($myID)){
	exit("No ID sent.");
}

$query = "INSERT INTO `coursecompleted` (`courseID`,`studentID`) SELECT `courseID`,`studentID` FROM `courseenrolled` WHERE `courseenrolled`.`courseenrolledID` = $myID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{

		echo $mysqlConn->insert_id;
		//delete old row
		$query = "DELETE FROM `courseenrolled` WHERE `courseenrolled`.`courseenrolledID` = $myID";
		$mysqlConn->query($query);
}
else
{
			error_log("\n<br />Warning: query(s) failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
			echo "0";
}
?>
