<?php
require_once("php/functions.php");
userCheckPrivilege(5);

$tournamentID = intval($_REQUEST['myID']);
$query = "SELECT `published` from `tournament` WHERE `tournament`.`tournamentID` = $tournamentID AND `tournament`.`schoolID` = `schoolID` = " . $_SESSION['userData']['schoolID'] ;
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if(empty($result))
{
	echo "Query Tournament Publish Failed.";
	exit("0");
}

$row = $result->fetch_assoc();

if($row['published'])
{
    $query = "UPDATE `tournament` SET `published` = '0' WHERE `tournament`.`tournamentID` = $tournamentID";
    $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    exit("2");
}
$query = "UPDATE `tournament` SET `published` = '1' WHERE `tournament`.`tournamentID` = $tournamentID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
exit("1");
?>