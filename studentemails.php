<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");
userCheckPrivilege(3);
echo getStudentEmails($mysqlConn, NULL, FALSE);
?>
<br><input class='button fa' type='button' onclick='window.history.back()' value='&#xf0a8; Return' />
