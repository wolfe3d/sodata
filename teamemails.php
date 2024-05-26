<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(3);
$teamID = intval($_POST['myID']);

echo getTeamEmails($teamID, NULL,0);?>
<p>
<button class='btn btn-outline-secondary' onclick='window.history.back()'><span class='bi bi-arrow-left-circle'></span> Return</button>
</p>
