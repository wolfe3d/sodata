<?php
//This changes either data in Coach table or Student table
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
//text output
$output = "";

$myID = intval($_POST['myid']);
if(empty($myID))
{
	echo "No ID given.";
	exit;
}

$tableName = $mysqlConn->real_escape_string($_POST['mytable']);
if(empty($tableName))
{
	echo "No table name given.";
	exit;
}


$query = "DELETE FROM `$tableName` WHERE `$tableName`.`".$tableName."ID` = $myID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

//Make changes to database
if ($result === TRUE)
{
	echo "1";
}
else
{
	echo $query;
	echo " " . $mysqlConn->error;
}
?>
