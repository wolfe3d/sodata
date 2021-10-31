<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");
userCheckPrivilege(3);
$teamID = intval($_POST['myID']);

    echo getTeamEmails($mysqlConn, $teamID);
?>