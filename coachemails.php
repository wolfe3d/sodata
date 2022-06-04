<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/functions.php");
userCheckPrivilege(3);
$year = isset($_REQUEST['myID'])?intval($_REQUEST['myID']):getCurrentSOYear();
echo getCoachesEmails($mysqlConn, $year);
?>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()'><span class='fa fa-arrow-circle-left'></span> Return</button></p>
