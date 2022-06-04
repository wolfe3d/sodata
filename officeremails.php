<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/functions.php");
userCheckPrivilege(3);
$year = intval($_REQUEST['myID']);
echo getOfficerEmails($mysqlConn, $year)."<br><input class='button fa' type='button' onclick=\"window.location='#leaders-year'\" value='&#xf0a8; Return' />";
?>
