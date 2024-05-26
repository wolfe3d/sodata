<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(4);
echo getTeamEmails(NULL, NULL, true);
?>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>
