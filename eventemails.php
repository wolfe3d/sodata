<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/functions.php");
userCheckPrivilege(2);

$event = intval($_POST['myID']);
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['userID']);
//semester teams tournament hardcoded, change later
$emails = "<h2>".getEventName($mysqlConn,$event)."</h2>";
$fallRosterDate = strval(getCurrentSOYear()-1)."-08-01";
$emails.="<h3>Fall Roster</h3>";
$query = "SELECT DISTINCT `first`, `last`, `email`, `emailSchool` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE eventID = $event and dateTournament = '$fallRosterDate' and `student`.`studentID` != $studentID and `student`.`active` = 1";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

while ($row = $result->fetch_assoc()):
    if($row['email']){
        $emails.=$row['first'] . " " . $row['last']." &lt;";
        $emails.=$row['email'] . "&gt;; ";
    }

    if($row['emailSchool']){
        $emails.="&lt;".$row['emailSchool'] . "&gt;; ";
    }
    $emails.="<br>";

endwhile;

$query = "SELECT DISTINCT `first`, `last`, `email`, `emailSchool` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE eventID = $event and `student`.`studentID` != $studentID and `student`.`active` = 1 group by `first`, `last`,`email`,`emailSchool` having sum(case when `dateTournament` > '$fallRosterDate' then 1 else 0 end) > 0 AND sum(case when `dateTournament` = '$fallRosterDate' then 1 else 0 end) = 0";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if($result->num_rows>0){
    $emails.="<br><br><h3>Other students assigned to tournaments</h3>";
    while ($row = $result->fetch_assoc()):
        if($row['email']){
            $emails.=$row['first'] . " " . $row['last']." &lt;";
            $emails.=$row['email'] . "&gt;; ";
        }

        if($row['emailSchool']){
            $emails.="&lt;".$row['emailSchool'] . "&gt;; ";
        }
        $emails.="<br>";

    endwhile;
}
$emails.= "<br><br><input class='button fa' type='button' onclick=\"window.location='#events'\" value='&#xf0a8; Return' />";
echo $emails;
?>
