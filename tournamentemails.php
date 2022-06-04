<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(3);
$team = intval($_POST['myID']);

echo getTeamEmails($mysqlConn, NULL, $tournamentID,0);?>
<p>
<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='fa fa-arrow-circle-left'></span> Return</button>
</p>
