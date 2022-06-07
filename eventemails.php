<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(2);

$event = intval($_POST['myID']);
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['userID']);
$studentIDWhere = "";
if($studentID)
{
	$studentIDWhere ="AND `student`.`studentID` != $studentID";
}
//TODO: This will show students assigned different years - not just this year fix this.
//semester teams tournament hardcoded, change later
$emails = "<h2>".getEventName($mysqlConn,$event)."</h2>";
$query = "SELECT DISTINCT `first`, `last`, `email`, `emailSchool` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID`
INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE `student`.`schoolID`= " .$_SESSION['userData']['schoolID']. " AND `eventID` = $event and `notCompetition`= 1 AND `student`.`active` = 1";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$emails.="<h3>Assigned to Event</h3>";
if($result && mysqli_num_rows($result)>0)
{
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


$query = "SELECT DISTINCT `first`, `last`, `email`, `emailSchool` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID`
INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE `eventID` = $event
AND `notCompetition`=0 $studentIDWhere
AND `student`.`active` = 1 group by `first`, `last`,`email`,`emailSchool` ";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if($result && $result->num_rows>0){
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
}
$emails.= "<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>";
echo $emails;
?>
