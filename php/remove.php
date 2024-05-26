<?php
require_once  ("../connectsodb.php");

function deletefromTable($tableName,$IDRowName,$myID)
{
	global $mysqlConn;
	$query = "DELETE FROM `$tableName` WHERE `$tableName`.`$IDRowName` = $myID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
}
function checkinTable($tableName,$IDRowName,$myID)
{
	global $mysqlConn;
	$query = "SELECT * FROM `$tableName` WHERE `$tableName`.`$IDRowName` = $myID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result and $result->num_rows){
		return 1;
	}
	return 0;
}
function checkNotSchoolID($schoolID, $tableName,$IDRowName,$myID)
{
	global $mysqlConn;
	$query =  "SELECT * FROM `$tableName` WHERE `$tableName`.`$IDRowName` = $myID AND `$tableName`.`schoolID`=$schoolID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result and $result->num_rows){
		return 0;
	}
	return 1;
}
?>
