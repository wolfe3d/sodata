<?php
require_once("../connectsodb.php");
require_once("checksession.php"); //Check to make sure user is logged in and has privileges
require_once("functions.php");

$userID = $_SESSION['userData']['id'];
$date = date('Y-m-d', time());
$query = "SELECT * FROM `student` INNER JOIN `teammate` ON `student`.`studentID`=`teammate`.`studentID` INNER JOIN `team` ON `teammate`.`teamID` = `team`.`teamID` INNER JOIN `tournament` ON `team`.`tournamentID` = `tournament`.`tournamentID` WHERE `userID` = $userID";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$tournaments = '';

while ($row = $result->fetch_assoc()):
    if($row['dateTournament'] > $date){
        $tournaments.="<div id=\"".$row['tournamentName']."\">";
        $tournaments.="<h3><a href=\"#tournament-view-".$row['tournamentID']."\">".$row['tournamentName']." - ".$row['teamName']." Team</a></h3>";
    }
endwhile;
?>
