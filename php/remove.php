<?php
function deletefromTable($db, $tableName,$IDRowName,$myID)
{
	$query = "DELETE FROM `$tableName` WHERE `$tableName`.`$IDRowName` = $myID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
}
function checkinTable($db, $tableName,$IDRowName,$myID)
{
	$query = "SELECT * FROM `$tableName` WHERE `$tableName`.`$IDRowName` = $myID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result and $result->num_rows){
		return 1;
	}
	return 0;
}
function checkNotSchoolID($db, $schoolID, $tableName,$IDRowName,$myID)
{
	$query =  "SELECT * FROM `$tableName` WHERE `$tableName`.`$IDRowName` = $myID AND `$tableName`.`schoolID`=$schoolID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result and $result->num_rows){
		return 0;
	}
	return 1;
}
?>
