<?php
//This changes either data in Coach table or Student table

require_once ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
//text output
$output = "";

$myID = intval($_POST['myid']);
$table = $mysqlConn->real_escape_string($_POST['mytable']);
$field = $mysqlConn->real_escape_string($_POST['myfield']);
$value = $mysqlConn->real_escape_string($_POST['myvalue']);

//check to see if user has a valid studentID
$query = "SELECT `userID` FROM `$table` WHERE `$table`.`".$table."ID` = $myID";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$row = $result->fetch_assoc();

//Check permissions to make this user is either an admin or editing their own data
if($_SESSION['userData']['privilege']<2 && $_SESSION['userData'][`id`]!=$row['userID'])
{
	echo "The current user does not have privilege for this change.";
	exit;
}

//Make changes to database
$queryUpdate = "UPDATE `$table` SET `$field`='$value' WHERE `$table`.`".$table."ID` = $myID";
if ($mysqlConn->query($queryUpdate) === TRUE)
{
	echo "*Record Edited";
}
else
{
	echo $queryUpdate;
	echo "Error modifying Student/Coach";
}
?>
