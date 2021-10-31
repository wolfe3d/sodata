<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");
userCheckPrivilege(3);

$year = isset($_POST['myID'])?intval($_POST['myID']):getCurrentSOYear();
$coachQuery = "SELECT * FROM `coach`";
$emails = "";

$result = $mysqlConn->query($coachQuery) or print("\n<br />Warning: query failed:$coachQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
while ($row = $result->fetch_assoc()):
    if($row['emailSchool']){
        $emails.=$row['first'] . " " . $row['last'] . " <";
        $emails.=$row['emailSchool'] . ">; ";
    }

endwhile;

echo $emails;
?>
