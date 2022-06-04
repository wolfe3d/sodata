<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/functions.php");
userCheckPrivilege(3);
$year = intval($_REQUEST['myID']);
echo getLeaderEmails($mysqlConn, $year);
?>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()'><span class='fa fa-arrow-circle-left'></span> Return</button></p>
