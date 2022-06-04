<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(3);
$year = intval($_REQUEST['myID']);
echo getOfficerEmails($mysqlConn, $year)."<br><input class='button fa' type='button' onclick=\"window.location='#leaders-year'\" value='&#xf0a8; Return' />";
?>
