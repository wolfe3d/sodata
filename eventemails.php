<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");
userCheckPrivilege(2);

$event = intval($_POST['myID']);
//semester teams tournament hardcoded, change later
$emails = "";

$query = "SELECT * FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE `tournamentID` = 12 AND eventID = $event";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
while ($row = $result->fetch_assoc()):
    if($row['email']){
        $emails.=$row['first'] . " " . $row['last']." <";
        $emails.=$row['email'] . ">; ";
    }

    if($row['emailSchool']){
        $emails.=$row['first'] . " " . $row['last'] . " <";
        $emails.=$row['emailSchool'] . ">; ";
    }

endwhile;

echo htmlspecialchars($emails);
?>
