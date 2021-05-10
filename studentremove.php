<?php
require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);

$studentID = intval($_REQUEST['studentID']);
if($studentID && $_SESSION['userData']['privilege']>2)
{
	//Remove student from all tables
	deleteStudentfromTable($mysqlConn,"award",$studentID);
	deleteStudentfromTable($mysqlConn,"coursecompleted",$studentID);
	deleteStudentfromTable($mysqlConn,"courseenrolled",$studentID);
	deleteStudentfromTable($mysqlConn,"eventchoice",$studentID);
	deleteStudentfromTable($mysqlConn,"officer",$studentID);
	deleteStudentfromTable($mysqlConn,"studentPlacement",$studentID);
	deleteStudentfromTable($mysqlConn,"student",$studentID);

	echo "1";
	exit;
}
echo "0";

function deleteStudentfromTable($db, $tableName,$studentID)
{
	$query = "DELETE FROM `$tableName` WHERE `$tableName`.`studentID` = $studentID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
}
?>
