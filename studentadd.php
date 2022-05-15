<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
require_once  ("functions.php");

$first = $mysqlConn->real_escape_string($_POST['first']);
$last = $mysqlConn->real_escape_string($_POST['last']);
$yearGraduating = intval($_POST['yearGraduating']);
$schoolID = $mysqlConn->real_escape_string($_POST['schoolID']);
$studentschoolID = $mysqlConn->real_escape_string($_POST['studentschoolID']);
$scilympiadID = $mysqlConn->real_escape_string($_POST['scilympiadID']);
$email = $mysqlConn->real_escape_string($_POST['email']);
$emailSchool = $mysqlConn->real_escape_string($_POST['emailSchool']);
$phoneType = intval(getIfSet($_POST['phoneType'],1));
$phone = $mysqlConn->real_escape_string($_POST['phone']);
$parent1Last = $mysqlConn->real_escape_string($_POST['parent1Last']);
$parent1First = $mysqlConn->real_escape_string($_POST['parent1First']);
$parent1Phone = $mysqlConn->real_escape_string($_POST['parent1Phone']);
$parent1Email = $mysqlConn->real_escape_string($_POST['parent1Email']);
$parent2Last = $mysqlConn->real_escape_string($_POST['parent2Last']);
$parent2First = $mysqlConn->real_escape_string($_POST['parent2First']);
$parent2Phone = $mysqlConn->real_escape_string($_POST['parent2Phone']);
$parent2Email = $mysqlConn->real_escape_string($_POST['parent2Email']);

$query = "INSERT INTO `student` (`first`,`last`,`schoolID`,`studentschoolID`,`scilympiadID`,`yearGraduating`,`email`,`emailSchool`,`phoneType`,`phone`,`parent1Last`,`parent1First`,`parent1Phone`,`parent1Email`,`parent2Last`,`parent2First`,`parent2Phone`,`parent2Email`,`uniqueToken`) VALUES ('$first', '$last', '$schoolID', `studentschoolID`, '$scilympiadID', '$yearGraduating', '$email', '$emailSchool', '$phoneType', '$phone', '$parent1Last', '$parent1First', '$parent1Phone', '$parent1Email', '$parent2Last', '$parent2First', '$parent2Phone', '$parent2Email', 1)";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($result)
{
	echo $mysqlConn->insert_id;
}
else {
	exit($query);
}
?>
