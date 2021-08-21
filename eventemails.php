<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");
userCheckPrivilege(2);

$event = intval($_POST['myID']);
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['id']);
//semester teams tournament hardcoded, change later
$emails = "";
$fallRosterDate = strval(getCurrentSOYear()-1)."-07-31";
$query = "SELECT DISTINCT `first`, `last`, `email`, `emailSchool` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE eventID = $event and dateTournament > '$fallRosterDate' and `student`.`studentID` != $studentID";
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

// echo htmlspecialchars($emails);
echo $emails;
?>
