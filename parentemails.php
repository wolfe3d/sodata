<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(4);

$query = "SELECT * FROM `student` WHERE `active` = 1";
$emails = "";

$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
while ($row = $result->fetch_assoc()):
    if($row['parent1Email']){
        $emails.=$row['parent1First'] . " " . $row['parent1Last'] . " <";
        $emails.=$row['parent1Email'] . ">; ";
    }

    if($row['parent2Email']){
        $emails.=$row['parent2First'] . " " . $row['parent2Last'] . " <";
        $emails.=$row['parent2Email'] . ">; ";
    }

endwhile;

echo $emails;
require_once  ("coachemails.php");
?>
