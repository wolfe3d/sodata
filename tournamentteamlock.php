<?php
require_once("php/functions.php");
userCheckPrivilege(5);

$teamID = intval($_REQUEST['myID']);
$query = "SELECT `team`.`locked` from `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID` WHERE `team`.`teamID` = $teamID AND `tournament`.`schoolID` = 1";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if(empty($result))
{
	echo "Query Tournament Publish Failed.";
	exit("0");
}

$row = $result->fetch_assoc();

if($row['locked'])
{
    $query = "UPDATE `team` SET `locked` = '0' WHERE `team`.`teamID` = $teamID";
    $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    exit("2");
}
$query = "UPDATE `team` SET `locked` = '1' WHERE `team`.`teamID` = $teamID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
exit("1");
?>