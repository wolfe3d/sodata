<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(3);
$year = isset($_REQUEST['myID'])?intval($_REQUEST['myID']):getCurrentSOYear();
echo getCoachesEmails($year);
?>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>
