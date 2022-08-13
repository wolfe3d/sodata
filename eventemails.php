<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");
userCheckPrivilege(2);

$event = intval($_POST['myID']);
$year = getCurrentSOYear();
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['userID']);
$studentIDWhere = "";
if($studentID)
{
	$studentIDWhere ="AND `student`.`studentID` != $studentID";
}
//print each noncompetition tournament event members
$eventName = getEventName($mysqlConn,$event);
$emails="<h3>$eventName</h3>";
$query = "SELECT DISTINCT `tournamentID`,`tournamentName` FROM `tournament` WHERE  `schoolID` = " . $_SESSION['userData']['schoolID'] . " AND `year`=$year AND `notCompetition`= 1";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if($result && $result->num_rows>0){
    while ($row = $result->fetch_assoc()):
			$emails.=getEventEmails($mysqlConn, $row['tournamentID'], $row['tournamentName'], $event, $year);
		endwhile;
	}

//TODO Change me
$query = "SELECT DISTINCT `first`, `last`, `email`, `emailSchool` FROM `tournamentevent` INNER JOIN `teammateplace` ON `tournamentevent`.`tournamenteventID` = `teammateplace`.`tournamenteventID` INNER JOIN `tournament` on `tournamentevent`.`tournamentID` = `tournament`.`tournamentID`
INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID`
WHERE `student`.`schoolID` = " . $_SESSION['userData']['schoolID'] .
" AND `eventID` = '$event'
AND `notCompetition`=0 $studentIDWhere
AND `student`.`active` = 1 group by `first`, `last`,`email`,`emailSchool` ";
$result = $mysqlConn->query($query) or print_r("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if($result && $result->num_rows>0){
    $emails.="<br><br><h3>All active students who have been assigned to or competed in a tournament in this event</h3>";
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
$emails.= "<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>";
echo $emails;
?>
