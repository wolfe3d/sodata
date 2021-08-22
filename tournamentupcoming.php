<?php
require_once("../connectsodb.php");
require_once("checksession.php"); //Check to make sure user is logged in and has privileges
require_once("functions.php");

$userID = $_SESSION['userData']['id'];
$fallRosterDate = strval(getCurrentSOYear()-1)."-08-01";
$date = date('Y-m-d', time());
//fallRosterDate should be changed to a part of the table that indicated that this is a roster (not a tournament)
$query = "SELECT * FROM `student` INNER JOIN `teammate` ON `student`.`studentID`=`teammate`.`studentID` INNER JOIN `team` ON `teammate`.`teamID` = `team`.`teamID` INNER JOIN `tournament` ON `team`.`tournamentID` = `tournament`.`tournamentID` WHERE `userID` = $userID and `dateTournament` > '$date' and `dateTournament` != '$fallRosterDate'";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$tournaments = '';

while ($row = $result->fetch_assoc()):
    $tournaments.="<div id=\"".$row['tournamentName']."\">";
    $tournaments.="<h3><a href=\"#tournament-view-".$row['tournamentID']."\">".$row['tournamentName']." - ".$row['teamName']." Team</a></h3>";
    $tournamentID = $row['tournamentID'];
    $tournamentQuery = "SELECT `student`.`studentID`, `tournamentevent`.`tournamenteventID`, `teamID`,`userID`,`event`.`eventID`, `event`.`event` FROM `teammateplace` INNER JOIN `student` on `teammateplace`.`studentID` = `student`.`studentID` INNER JOIN `tournamentevent` on `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID` inner join `event` on `tournamentevent`.`eventID` = `event`.`eventID` where `tournamentID` = $tournamentID and `userID` = $userID";
    // $tournaments.=$tournamentQuery;
    $tournamentResult = $mysqlConn->query($tournamentQuery) or print("\n<br />Warning: query failed:$tournamentQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    if($tournamentResult->num_rows > 0){
        $tournaments.="Your events and partners:<br><ul>";
        while ($row = $tournamentResult->fetch_assoc()):
            $tournamenteventID = $row['tournamenteventID'];
            $teamID = $row['teamID'];
            $studentID = getStudentID($mysqlConn, $userID);
            $tournaments.="<li>".$row['event'];
            $partnerQuery = "SELECT * FROM `teammateplace` INNER JOIN `student` ON `teammateplace`.`studentID` = `student`.`studentID` WHERE `tournamenteventID` = $tournamenteventID and `teamID` = $teamID and `student`.`studentID` != $studentID";
            $partnerResult = $mysqlConn->query($partnerQuery) or print("\n<br />Warning: query failed:$partnerQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
            if($partnerResult){
                $tournaments.="<ul>";
                while ($row = $partnerResult->fetch_assoc()):
                    $tournaments.="<li>".$row['first']." ".$row['last']." ".$row['email']."</li>";
                endwhile;
                $tournaments.="</ul>";
            }
            $tournaments.="</li>";
        endwhile;
        $tournaments.="</ul>";
    }
    $tournaments.="</div>";
endwhile;
?>
