<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
require_once  ("functions.php");

    function getIfSet(&$value, $default = NULL)
{
	return isset($value) ? $value : $default;
}

$active = intval($_POST['active']);
$first = $mysqlConn->real_escape_string($_POST['first']);
$last = $mysqlConn->real_escape_string($_POST['last']);
$yearGraduating = intval($_POST['yearGraduating']);
$googleEmail = $mysqlConn->real_escape_string($_POST['email']);
$schoolEmail = $mysqlConn->real_escape_string($_POST['emailSchool']);
$phoneType = $mysqlConn->real_escape_string($_POST['phoneType']);
$phone = $mysqlConn->real_escape_string($_POST['phone']);
$parent1Last = $mysqlConn->real_escape_string($_POST['parent1Last']);
$parent1First = $mysqlConn->real_escape_string($_POST['parent1First']);
$parent1Phone = $mysqlConn->real_escape_string($_POST['parent1Phone']);
$parent1Email = $mysqlConn->real_escape_string($_POST['parent1Email']);
$parent2Last = $mysqlConn->real_escape_string($_POST['parent2Last']);
$parent2First = $mysqlConn->real_escape_string($_POST['parent2First']);
$parent2Phone = $mysqlConn->real_escape_string($_POST['parent2Phone']);
$parent2Email = $mysqlConn->real_escape_string($_POST['parent2Email']);

$query = "INSERT INTO `student` (`first`,`last`,`yearGraduating`,`email`,`emailSchool`,`phoneType`,`phone`,`parent1Last`,`parent1First`,`parent1Phone`,`parent1Email`,`parent2Last`,`parent2First`,`parent2Phone`,`parent2Email`,`uniqueToken`) VALUES ('$first', '$last', '$yearGraduating', '$email', '$emailSchool', '$phoneType', '$phone', '$parent1Last', '$parent1First', '$parent1Phone', '$parent1Email', '$parent2Last', '$parent2First', '$parent2Phone', '$parent2Email', 1)";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($result)
{
	echo $mysqlConn->insert_id;
}
else {
	exit($query);
}
?>
