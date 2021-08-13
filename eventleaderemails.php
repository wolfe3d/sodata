<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");
userCheckPrivilege(3);

$year = isset($_POST['myID'])?intval($_POST['myID']):getCurrentSOYear();
$query = "SELECT * FROM `eventyear` INNER JOIN `student` ON `eventyear`.`studentID`= `student`.`studentID` INNER JOIN `event` ON `eventyear`.`eventID`=`event`.`eventID` WHERE `year`=$year";
$emails = "";

$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
while ($row = $result->fetch_assoc()):
    if($row['email']){
        $emails.=$row['first'] . " " . $row['last'] . " <";
        $emails.=$row['email'] . ">; ";
    }

    if($row['emailSchool']){
        $emails.=$row['first'] . " " . $row['last'] . " <";
        $emails.=$row['emailSchool'] . ">; ";
    }

endwhile;

echo $emails;
require_once  ("coachemails.php");
?>
