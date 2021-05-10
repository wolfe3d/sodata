<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
//text output
$output = "";

$myID = intval($_REQUEST['myID']);

$query = "INSERT INTO `coursecompleted` (`courseID`,`studentID`) SELECT `courseID`,`studentID` FROM `courseenrolled` WHERE `courseenrolled`.`myID` = $myID";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{

		echo $mysqlConn->insert_id;
		//delete old row
		$query = "DELETE FROM `courseenrolled` WHERE `courseenrolled`.`myID` = $myID";
		$mysqlConn->query($query);
}
else
{
			print("\n<br />Warning: query(s) failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
			echo "0";
}
?>
