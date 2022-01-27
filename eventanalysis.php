<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");
userCheckPrivilege(2);

$event = intval($_POST['myID']);
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['userID']);
//semester teams tournament hardcoded, change later
$output = "<h2>".getEventName($mysqlConn,$event)." Analysis</h2>";
$fallRosterDate = strval(getCurrentSOYear()-1)."-08-01";
$query = "SELECT `first`, `last`, `email`, `emailSchool`,`place`,`tournamentName` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE eventID = $event and `student`.`active` = 1 and `place` IS NOT NULL Order By `last`, `first`";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

while ($row = $result->fetch_assoc()):
    if($row['email']){
        $output.=$row['first'] . " " . $row['last']." ";
        $output.=$row['tournamentName'] . " " . $row['place'];
    }

    $output.="<br>";

endwhile;

$query = "SELECT DISTINCT `first`, `last`, `email`, `emailSchool` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE eventID = $event and `student`.`studentID` != $studentID and `student`.`active` = 1 group by `first`, `last`,`email`,`emailSchool` having sum(case when `dateTournament` > '$fallRosterDate' then 1 else 0 end) > 0 AND sum(case when `dateTournament` = '$fallRosterDate' then 1 else 0 end) = 0";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if($result->num_rows>0){
    $output.="<br><br><h3>Other students assigned to tournaments</h3>";
    while ($row = $result->fetch_assoc()):
        if($row['email']){
            $output.=$row['first'] . " " . $row['last']." &lt;";
            $output.=$row['email'] . "&gt;; ";
        }

        if($row['emailSchool']){
            $output.="&lt;".$row['emailSchool'] . "&gt;; ";
        }
        $output.="<br>";

    endwhile;
}
$output.= "<br><br><input class='button fa' type='button' onclick=\"window.location='#events'\" value='&#xf0a8; Return' />";
echo $output;
?>
