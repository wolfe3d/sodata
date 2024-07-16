<?php
//This changes either data in Coach table or Student table
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(2);
//text output
$output = "";

$myID = intval($_POST['myid']);
$table = $mysqlConn->real_escape_string($_POST['mytable']);
$field = $mysqlConn->real_escape_string($_POST['myfield']);
$value = $mysqlConn->real_escape_string($_POST['myvalue']);
$studentID = isset($_POST['studentID']) ? intval($_POST['studentID']) : null;

$tempTable = '';

//special cases for times
if($field=="timeStart" || $field=="timeEnd")
{
	$value = date('Y-m-d H:i:s',strtotime($value));
}
if($table == 'attendance' || $table == 'engagement' || $table == 'homework')
{
	$tempTable = $table;
	$table = 'meetingattendance';
}
//check to see if user has a valid ID
$query = "SELECT `".$table."ID` FROM `$table` WHERE `$table`.`".$table."ID` = $myID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if($result && mysqli_num_rows($result)>0){
	$row = $result->fetch_assoc();

	//Check permissions to make this user is an admin or editing their own data
	/*if(userCheckPrivilege(2) && $_SESSION['userData'][`id`]!=$row['userID'])
	{
	echo "The current user does not have privilege for this change.";
	exit;
}*/
//Make changes to database
if($table == 'meetingattendance') // exception for meetingattendance table
{
	$query = "UPDATE `$table` SET `$tempTable`='$value' WHERE `$table`.`studentID` = $field";
	// $queryEscaped = json_encode($query);
	// echo '<script>console.log('.$queryEscaped.');</script>';
}
else
{
	$query = "UPDATE `$table` SET `$field`='$value' WHERE `$table`.`".$table."ID` = $myID";
}
if ($mysqlConn->query($query) === TRUE)
{
	exit ("1");
}
}
exit($query . " " . $mysqlConn->error);
?>
