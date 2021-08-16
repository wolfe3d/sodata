<?php
require_once("../connectsodb.php");
require_once("checksession.php"); //Check to make sure user is logged in and has privileges
require_once("functions.php");

$userID = $_SESSION['userData']['id'];
$date = date('Y-m-d', time());
$query = "SELECT * FROM `student` INNER JOIN `teammate` ON `student`.`studentID`=`teammate`.`studentID` INNER JOIN `team` ON `teammate`.`teamID` = `team`.`teamID` INNER JOIN `tournament` ON `team`.`tournamentID` = `tournament`.`tournamentID` WHERE `userID` = $userID and `dateTournament` < '$date'";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$tournaments = '';

while ($row = $result->fetch_assoc()):
    $tournaments.="<div id=\"".$row['tournamentName']."\">";
    $tournaments.="<h3>".$row['tournamentName']."</h3>";
    $tournamentID = $row['tournamentID'];
    $tournamentQuery = "SELECT `student`.`studentID`, `tournamentevent`.`tournamenteventID`, `teamID`,`userID`,`event`.`eventID`, `event`.`event` FROM `teammateplace` INNER JOIN `student` on `teammateplace`.`studentID` = `student`.`studentID` INNER JOIN `tournamentevent` on `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID` inner join `event` on `tournamentevent`.`eventID` = `event`.`eventID` where `tournamentID` = $tournamentID and `userID` = $userID";
    $tournamentResult = $mysqlConn->query($tournamentQuery) or print("\n<br />Warning: query failed:$tournamentQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    if($tournamentResult->num_rows > 0){
        $tournaments.="Your results:<br><ul>";
        while ($row = $tournamentResult->fetch_assoc()):
            $tournamenteventID = $row['tournamenteventID'];
            $teamID = $row['teamID'];
            $studentID = getStudentID($mysqlConn, $userID);
            $tournaments.="<li>".$row['event'].": ";
            $event = $row['event'];
            $placeQuery = "SELECT `event`,`place` FROM `teammateplace` INNER JOIN `student` on `teammateplace`.`studentID` = `student`.`studentID` INNER JOIN `tournamentevent` on `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID` inner join `event` on `tournamentevent`.`eventID` = `event`.`eventID` where `tournamentID` = $tournamentID and `userID` = $userID and `event` = '$event'";
            $placeResult = $mysqlConn->query($placeQuery) or print("\n<br />Warning: query failed:$placeQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
            if($placeResult){
                while ($row = $placeResult->fetch_assoc()):
                    $tournaments.=$row['place'];
                endwhile;
            }
            $tournaments.="</li>";
        endwhile;
        $tournaments.="</ul>";
    }
    $tournaments.="</div>";
endwhile;
?>
