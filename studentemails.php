<?php
header("Content-Type: text/plain");
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$query = "SELECT * FROM `student` WHERE `active` = 1";
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
