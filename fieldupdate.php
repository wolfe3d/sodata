<?php
//This changes either data in Coach table or Student table
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once("php/functions.php");

userCheckPrivilege(2);
//text output
$output = "";

$tableID = intval($_POST['myid']);
$table = $mysqlConn->real_escape_string($_POST['mytable']);
$field = $mysqlConn->real_escape_string($_POST['myfield']);
$value = $mysqlConn->real_escape_string($_POST['myvalue']);

fieldUpdate($table,$tableID,$field,$value);
?>
