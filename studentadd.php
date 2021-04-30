<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("functions.php");

$last = $mysqlConn->real_escape_string($_POST['addLast']);
$first = $mysqlConn->real_escape_string($_POST['addFirst']);
$yearGraduating = intval($_POST['yearGraduating']);
$email = $mysqlConn->real_escape_string($_POST['email']);
$emailAlt = $mysqlConn->real_escape_string($_POST['emailAlt']);
$phoneType = $mysqlConn->real_escape_string($_POST['phoneType']);
$phone = $mysqlConn->real_escape_string($_POST['phone']);
$parent1Last = $mysqlConn->real_escape_string($_POST['parent1Last']);
$parent1First = $mysqlConn->real_escape_string($_POST['parent1First']);
$parent1Email = $mysqlConn->real_escape_string($_POST['parent1Email']);
$parent1Phone = $mysqlConn->real_escape_string($_POST['parent1Phone']);
$parent2Last = $mysqlConn->real_escape_string($_POST['parent2Last']);
$parent2First = $mysqlConn->real_escape_string($_POST['parent2First']);
$parent2Email = $mysqlConn->real_escape_string($_POST['parent2Email']);
$parent2Phone = $mysqlConn->real_escape_string($_POST['parent2Phone']);

//check students for randomID
$uniqueToken = get_uniqueToken($mysqlConn, 'student');

$queryInsert = "INSERT INTO `student` (`studentID`, `uniqueToken`, `last`, `first`, `yearGraduating`, `email`, `emailAlt`, `phoneType`, `phone`, `parent1Last`, `parent1First`, `parent1Email`, `parent1Phone`, `parent2Last`, `parent2First`, `parent2Email`, `parent2Phone`) VALUES (NULL, '$uniqueToken', '$last', '$first', '$yearGraduating', '$email', '$emailAlt', '$phoneType', '$phone', '$parent1Last', '$parent1First', '$parent1Email', '$parent1Phone', '$parent2Last', '$parent2First', '$parent2Email', '$parent2Phone');";
if ($mysqlConn->query($queryInsert) === TRUE)
{
	echo "New record created.\n";
	include("students.php");
}
else
{
	echo json_encode(array("error"=>"Error_addStudent: $queryInsert $mysqlConn->error"));
}
?>
