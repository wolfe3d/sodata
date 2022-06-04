<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/functions.php");
userCheckPrivilege(3);
$tournamentID = intval($_POST['myID']);

echo getTeamEmails($mysqlConn, NULL, $tournamentID, true)."<br><input class='button fa' type='button' onclick=\"window.location='#tournament-view-".$tournamentID."'\" value='&#xf0a8; Return' />";
?>