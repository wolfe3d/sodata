<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);

$slideID = intval($_POST['slideID']);
$slideText = $mysqlConn->real_escape_string($_POST['slideText']);

if(empty($slideID)||empty($slideText))
{
	exit("Missing a required field");
}

$query = "UPDATE `slide` SET `slide`.`text`='$slideText' WHERE `slideID`=$slideID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if ($result)
		{
			exit("1");
		}
		else
		{
			exit("Unspecified error. Check database log.");
		}
?>
