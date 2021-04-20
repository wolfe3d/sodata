<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

//text output
$output = "";

$studentID = intval($_POST['studentID']);
$field = $mysqlConn->real_escape_string($_POST['myfield']);
$value = $mysqlConn->real_escape_string($_POST['myvalue']);

$queryUpdate = "UPDATE `students` SET `$field`='$value' WHERE `students`.`studentID` = $studentID;";
if ($mysqlConn->query($queryUpdate) === TRUE)
{
	echo "*Record Edited";
}
else
{
	echo json_encode(array("error"=>"Error_addStudent: $queryInsert $mysqlConn->error"));
}
?>
