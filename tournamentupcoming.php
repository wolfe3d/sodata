<?php
require_once("../connectsodb.php");
require_once("checksession.php"); //Check to make sure user is logged in and has privileges
require_once("functions.php");

$userID = $_SESSION['userData']['userID'];
$studentID = getStudentID($mysqlConn,$userID);
$fallRosterDate = strval(getCurrentSOYear()-1)."-08-01";
$date = date('Y-m-d', time());
//fallRosterDate should be changed to a part of the table that indicated that this is a roster (not a tournament)
$query = "SELECT `tournamentName`,`tournament`.`tournamentID`,`dateTournament`,`teamName` FROM `student` INNER JOIN `teammate` ON `student`.`studentID`=`teammate`.`studentID` INNER JOIN `team` ON `teammate`.`teamID` = `team`.`teamID` INNER JOIN `tournament` ON `team`.`tournamentID` = `tournament`.`tournamentID` WHERE `userID` = $userID and `dateTournament` >= '$date' and `dateTournament` != '$fallRosterDate' ORDER BY `dateTournament`";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$tournaments = '';

while ($row = $result->fetch_assoc()):
    {
    $tournaments.="<div id=\"".$row['tournamentName']."\">";
    $tournaments.="<a href=\"#tournament-view-".$row['tournamentID']."\"><h3>".$row['tournamentName']." - ".$row['teamName']." Team</h3></a>";
    $tournamentID = $row['tournamentID'];
    $tournaments.=studentTournamentSchedule($mysqlConn, $tournamentID, $studentID);
    }
    $tournaments.="</div>";
endwhile;
?>
