<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(3);
$teamID = intval($_POST['myID']);

echo getTeamEmails($mysqlConn, $teamID, NULL,0);?>
<p>
<button class='btn btn-outline-secondary' onclick='window.history.back()'><span class='fa fa-arrow-circle-left'></span> Return</button>
</p>
